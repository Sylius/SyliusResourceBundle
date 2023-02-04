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

/* grid/action/apply_transition.html.twig */
class __TwigTemplate_faf26e7ba6ad2df9d16e3135ee5cb281 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "grid/action/apply_transition.html.twig"));

        // line 1
        $context["labeled"] = ((twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "labeled", [], "any", true, true, false, 1)) ? (twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 1, $this->source); })()), "labeled", [], "any", false, false, false, 1)) : (true));
        // line 2
        echo "
";
        // line 3
        if ($this->extensions['SM\Extension\Twig\SMExtension']->can((isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 3, $this->source); })()), twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 3, $this->source); })()), "transition", [], "any", false, false, false, 3), twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 3, $this->source); })()), "graph", [], "any", false, false, false, 3))) {
            // line 4
            echo "    <form action=\"";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 4, $this->source); })()), "link", [], "any", false, false, false, 4), "route", [], "any", false, false, false, 4), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 4, $this->source); })()), "link", [], "any", false, false, false, 4), "parameters", [], "any", false, false, false, 4)), "html", null, true);
            echo "\" method=\"post\">
        <input type=\"hidden\" name=\"_csrf_token\" value=\"";
            // line 5
            echo twig_escape_filter($this->env, $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 5, $this->source); })()), "id", [], "any", false, false, false, 5)), "html", null, true);
            echo "\">
        <input type=\"hidden\" name=\"_method\" value=\"PUT\">
        <button class=\"ui loadable ";
            // line 7
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "class", [], "any", true, true, false, 7)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "class", [], "any", false, false, false, 7))) : ("")), "html", null, true);
            echo " ";
            if ((isset($context["labeled"]) || array_key_exists("labeled", $context) ? $context["labeled"] : (function () { throw new RuntimeError('Variable "labeled" does not exist.', 7, $this->source); })())) {
                echo "labeled";
            }
            echo " icon button\" type=\"submit\" data-js-disable=\".sylius-grid-table-wrapper button, .sylius-grid-table-wrapper a\">
            <i class=\"";
            // line 8
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 8, $this->source); })()), "icon", [], "any", false, false, false, 8), "html", null, true);
            echo " icon\"></i>
            ";
            // line 9
            if ((isset($context["labeled"]) || array_key_exists("labeled", $context) ? $context["labeled"] : (function () { throw new RuntimeError('Variable "labeled" does not exist.', 9, $this->source); })())) {
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(twig_get_attribute($this->env, $this->source, (isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 9, $this->source); })()), "label", [], "any", false, false, false, 9)), "html", null, true);
            }
            // line 10
            echo "        </button>
    </form>
";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "grid/action/apply_transition.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 10,  69 => 9,  65 => 8,  57 => 7,  52 => 5,  47 => 4,  45 => 3,  42 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% set labeled = options.labeled is defined ? options.labeled : true %}

{% if sm_can(data, options.transition, options.graph) %}
    <form action=\"{{ path(options.link.route, options.link.parameters) }}\" method=\"post\">
        <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token(data.id) }}\">
        <input type=\"hidden\" name=\"_method\" value=\"PUT\">
        <button class=\"ui loadable {{ options.class|default }} {% if labeled %}labeled{% endif %} icon button\" type=\"submit\" data-js-disable=\".sylius-grid-table-wrapper button, .sylius-grid-table-wrapper a\">
            <i class=\"{{ action.icon }} icon\"></i>
            {% if labeled %}{{ action.label|trans }}{% endif %}
        </button>
    </form>
{% endif %}
", "grid/action/apply_transition.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/tests/Application/templates/grid/action/apply_transition.html.twig");
    }
}
