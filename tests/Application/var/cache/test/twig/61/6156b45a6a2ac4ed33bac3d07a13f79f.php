<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* ScienceBook/index.html.twig */
class __TwigTemplate_1ecec4ed9724c3c8e2b1b18367afec5d extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "ScienceBook/index.html.twig"));

        // line 1
        echo "<h1>Books</h1>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Title</td>
            <td>Author</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
    ";
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["resources"]) || array_key_exists("resources", $context) ? $context["resources"] : (function () { throw new RuntimeError('Variable "resources" does not exist.', 12, $this->source); })()), "data", [], "any", false, false, false, 12));
        foreach ($context['_seq'] as $context["_key"] => $context["book"]) {
            // line 13
            echo "        <tr><td>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["book"], "id", [], "any", false, false, false, 13), "html", null, true);
            echo "</td><td>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["book"], "title", [], "any", false, false, false, 13), "html", null, true);
            echo "</td><td>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["book"], "authorFirstName", [], "any", false, false, false, 13), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["book"], "authorLastName", [], "any", false, false, false, 13), "html", null, true);
            echo "</td>
            <td>
                <form action=\"";
            // line 15
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_science_book_delete", ["id" => twig_get_attribute($this->env, $this->source, $context["book"], "id", [], "any", false, false, false, 15)]), "html", null, true);
            echo "\" method=\"POST\">
                    <input type=\"hidden\" name=\"_method\" value=\"DELETE\"/>
                    <input type=\"hidden\" name=\"_csrf_token\" value=\"";
            // line 17
            echo twig_escape_filter($this->env, $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken(twig_get_attribute($this->env, $this->source, $context["book"], "id", [], "any", false, false, false, 17)), "html", null, true);
            echo "\" />
                    <button type=\"submit\">Delete</button>
                </form>
            </td>
        </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['book'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "    </tbody>
</table>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "ScienceBook/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 23,  74 => 17,  69 => 15,  57 => 13,  53 => 12,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<h1>Books</h1>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Title</td>
            <td>Author</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
    {% for book in resources.data %}
        <tr><td>{{ book.id }}</td><td>{{ book.title }}</td><td>{{ book.authorFirstName }} {{ book.authorLastName }}</td>
            <td>
                <form action=\"{{ path('app_science_book_delete', {'id': book.id}) }}\" method=\"POST\">
                    <input type=\"hidden\" name=\"_method\" value=\"DELETE\"/>
                    <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token(book.id) }}\" />
                    <button type=\"submit\">Delete</button>
                </form>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>
", "ScienceBook/index.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/tests/Application/templates/ScienceBook/index.html.twig");
    }
}
