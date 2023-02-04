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

/* grid/bulk_action/delete.html.twig */
class __TwigTemplate_e5c8728300fb9a1168e94fc90e6eb013 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "grid/bulk_action/delete.html.twig"));

        // line 1
        $context["path"] = ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "url", [], "any", true, true, false, 1)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "url", [], "any", false, false, false, 1), $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath(((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "route", [], "any", true, true, false, 1)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "route", [], "any", false, false, false, 1), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 1, $this->source); })()), "requestConfiguration", [], "any", false, false, false, 1), "getRouteName", [0 => "bulk_delete"], "method", false, false, false, 1))) : (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 1, $this->source); })()), "requestConfiguration", [], "any", false, false, false, 1), "getRouteName", [0 => "bulk_delete"], "method", false, false, false, 1))), ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "parameters", [], "any", true, true, false, 1)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "parameters", [], "any", false, false, false, 1), [])) : ([]))))) : ($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath(((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "route", [], "any", true, true, false, 1)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "route", [], "any", false, false, false, 1), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 1, $this->source); })()), "requestConfiguration", [], "any", false, false, false, 1), "getRouteName", [0 => "bulk_delete"], "method", false, false, false, 1))) : (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 1, $this->source); })()), "requestConfiguration", [], "any", false, false, false, 1), "getRouteName", [0 => "bulk_delete"], "method", false, false, false, 1))), ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "parameters", [], "any", true, true, false, 1)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "link", [], "any", false, true, false, 1), "parameters", [], "any", false, false, false, 1), [])) : ([])))));
        // line 2
        echo "
<form action=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["path"]) || array_key_exists("path", $context) ? $context["path"] : (function () { throw new RuntimeError('Variable "path" does not exist.', 3, $this->source); })()), "html", null, true);
        echo "\" method=\"post\">
    <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
    <button type=\"submit\">
        <i class=\"icon trash\"></i> Bulk delete
    </button>
    <input type=\"hidden\" name=\"_csrf_token\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("bulk_delete"), "html", null, true);
        echo "\" />

    ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 10, $this->source); })()), "data", [], "any", false, false, false, 10));
        foreach ($context['_seq'] as $context["_key"] => $context["resource"]) {
            // line 11
            echo "        <input type=\"hidden\" name=\"ids[]\" value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["resource"], "id", [], "any", false, false, false, 11), "html", null, true);
            echo "\" />
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['resource'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "</form>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "grid/bulk_action/delete.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 13,  62 => 11,  58 => 10,  53 => 8,  45 => 3,  42 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% set path = options.link.url|default(path(options.link.route|default(grid.requestConfiguration.getRouteName('bulk_delete')), options.link.parameters|default({}))) %}

<form action=\"{{ path }}\" method=\"post\">
    <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
    <button type=\"submit\">
        <i class=\"icon trash\"></i> Bulk delete
    </button>
    <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token('bulk_delete') }}\" />

    {% for resource in grid.data %}
        <input type=\"hidden\" name=\"ids[]\" value=\"{{ resource.id }}\" />
    {% endfor %}
</form>
", "grid/bulk_action/delete.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/tests/Application/templates/grid/bulk_action/delete.html.twig");
    }
}
