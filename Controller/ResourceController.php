<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Pluralization;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base resource controller for Sylius.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class ResourceController extends FOSRestController
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * Constructor.
     *
     * @param string $bundlePrefix
     * @param string $resourceName
     * @param string $templateNamespace
     */
    public function __construct($bundlePrefix, $resourceName, $templateNamespace)
    {
        $this->configuration = new Configuration($bundlePrefix, $resourceName, $templateNamespace);
    }

    /**
     * Get configuration with the bound request.
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        $this->configuration->setRequest($this->getRequest());

        return $this->configuration;
    }

    /**
     * List collection (paginated by default) of resources.
     */
    public function indexAction(Request $request)
    {
        $config = $this->getConfiguration();

        $criteria = $config->getCriteria();
        $sorting = $config->getSorting();

        $pluralResourceName = Pluralization::pluralize($config->getResourceName());

        $view = $this
            ->view()
            ->setTemplate($this->getFullTemplateName('index.html'))
        ;

        if ($config->isCollectionPaginated()) {
            $paginator = $this
                ->getRepository()
                ->createPaginator($criteria, $sorting)
            ;

            $paginator->setCurrentPage($request->get('page', 1), true, true);
            $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

            $resources = $paginator->getCurrentPageResults();

            $data = $config->isHtmlRequest() ? array(
                $pluralResourceName => $resources,
                'paginator'         => $paginator
            ) : $resources;
        } else {
            $view->setTemplateVar($pluralResourceName);

            $data = $this
                ->getRepository()
                ->findBy($criteria, $sorting, $config->getCollectionLimit())
            ;
        }

        $view->setData($data);

        return $this->handleView($view);
    }

    /**
     * Show single resource by its identifier.
     */
    public function showAction()
    {
        $view =  $this
            ->view()
            ->setTemplate($this->getFullTemplateName('show.html'))
            ->setTemplateVar($this->getConfiguration()->getResourceName())
            ->setData($this->findByIdentifierOr404())
        ;

        return $this->handleView($view);
    }

    /**
     * Create new resource or just display the form.
     */
    public function createAction(Request $request)
    {
        $config = $this->getConfiguration();

        $resource = $this->createNew();
        $form = $this->getForm($resource);

        if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
            $this->create($resource);

            $this->setFlash('success', '%resource% has been successfully created.');

            return $this->redirectTo($resource);
        }

        if (!$config->isHtmlRequest()) {
            return $this->handleView($this->view($form));
        }

        $view = $this
            ->view()
            ->setTemplate($this->getFullTemplateName('create.html'))
            ->setData(array(
                $config->getResourceName() => $resource,
                'form'                     => $form->createView()
            ))
        ;


        return $this->handleView($view);
    }

    /**
     * Display the form for editing or update the resource.
     */
    public function updateAction(Request $request)
    {
        $config = $this->getConfiguration();

        $resource = $this->findByIdentifierOr404();
        $form = $this->getForm($resource);

        if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
            $this->update($resource);
            $this->setFlash('success', '%resource% has been updated.');

            return $this->redirectTo($resource);
        }

        if (!$config->isHtmlRequest()) {
            return $this->handleView($this->view($form));
        }

        $view = $this
            ->view()
            ->setTemplate($this->getFullTemplateName('update.html'))
            ->setData(array(
                $config->getResourceName() => $resource,
                'form'                     => $form->createView()
            ))
        ;

        return $this->handleView($view);
    }

    /**
     * Delete resource.
     */
    public function deleteAction()
    {
        $resource = $this->findByIdentifierOr404();
        $this->delete($resource);
        $this->setFlash('success', '%resource% has been deleted.');

        return $this->redirectToCollection($resource);
    }

    /**
     * Use repository to create a new resource object.
     *
     * @return object
     */
    public function createNew()
    {
        return $this
            ->getRepository()
            ->createNew()
        ;
    }

    /**
     * Get a form instance for given resource.
     * If no custom form is specified in route defaults as "_form",
     * then a default name is generated using the template "%bundlePrefix%_%resourceName%".
     *
     * @return FormInterface
     */
    public function getForm($resource = null)
    {
        return $this->createForm($this->getConfiguration()->getFormType(), $resource);
    }

    public function redirectTo($resource)
    {
        return $this->redirectToRoute(
            $this->getRedirectRoute('show'),
            array('id' => $resource->getId())
        );
    }

    protected function redirectToReferer()
    {
        return $this->handleView($this->redirectView($this->getRequest()->headers->get('referer')));
    }

    public function redirectToCollection($resource)
    {
        return $this->redirectToRoute($this->getRedirectRoute('list'));
    }

    public function redirectToRoute($route, array $data = array())
    {
        if ('referer' === $route) {
            return $this->redirectToReferer();
        }

        return $this->handleView($this->routeRedirectView($route, $data));
    }

    public function getRedirectRoute($name)
    {
        $config = $this->getConfiguration();

        if (null !== $route = $config->getRedirect()) {
            return $route;
        }

        return sprintf('%s_%s_%s',
            $config->getBundlePrefix(),
            $config->getResourceName(),
            $name
        );
    }

    public function getManager()
    {
        return $this->getService('manager');
    }

    public function create($resource)
    {
        $this->dispatchEvent('pre_create', $resource);
        $this->persistAndFlush($resource);
        $this->dispatchEvent('post_create', $resource);
    }

    public function update($resource)
    {
        $this->dispatchEvent('pre_update', $resource);
        $this->persistAndFlush($resource);
        $this->dispatchEvent('post_update', $resource);
    }

    public function delete($resource)
    {
        $this->dispatchEvent('pre_delete', $resource);
        $this->removeAndFlush($resource);
        $this->dispatchEvent('post_delete', $resource);
    }

    public function persistAndFlush($resource)
    {
        $manager = $this->getManager();

        $manager->persist($resource);
        $manager->flush();
    }

    public function removeAndFlush($resource)
    {
        $manager = $this->getManager();

        $manager->remove($resource);
        $manager->flush();
    }

    public function getRepository()
    {
        return $this->getService('repository');
    }

    public function findByIdentifierOr404()
    {
        return $this->findOr404($this->getConfiguration()->getIdentifierCriteria());
    }

    public function findOr404(array $criteria)
    {
        if (!$resource = $this->getRepository()->findOneBy($criteria)) {
            throw new NotFoundHttpException(sprintf('Requested %s does not exist', $this->getConfiguration()->getResourceName()));
        }

        return $resource;
    }

    public function renderResponse($templateName, array $parameters = array())
    {
        return $this->render($this->getFullTemplateName($templateName), $parameters);
    }

    public function getFullTemplateName($name)
    {
        $config = $this->getConfiguration();

        if (null !== $template = $config->getTemplate()) {
            return $template;
        }

        return sprintf('%s:%s.%s',
            $config->getTemplateNamespace(),
            $name,
            $this->getEngine()
        );
    }

    public function dispatchEvent($name, $resource)
    {
        $config = $this->getConfiguration();

        $this->get('event_dispatcher')->dispatch(sprintf('%s.%s.%s', $config->getBundlePrefix(), $config->getResourceName(), $name), new GenericEvent($resource));
    }

    public function getEngine()
    {
        return $this->container->getParameter(sprintf('%s.engine', $this->getConfiguration()->getBundlePrefix()));
    }

    protected function setFlash($type, $message)
    {
        $config = $this->getConfiguration();

        if (null !== $customMessage = $config->getFlashMessage()) {
            $message = $customMessage;
        }

        $translatedMessage = $this->get('translator')->trans(
            $message,
            array('%resource%' => ucfirst($config->getResourceName())),
            'flashes'
        );

        return $this
            ->get('session')
            ->getFlashBag()
            ->add($type, $translatedMessage)
        ;
    }

    protected function getService($name)
    {
        return $this->get($this->getConfiguration()->getServiceName($name));
    }
}
