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

/* @SyliusResource/Twig/sorting.html.twig */
class __TwigTemplate_a3c8a501d529d4f2fe1ffa618ca7dc93 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@SyliusResource/Twig/sorting.html.twig"));

        // line 1
        echo "<a href=\"";
        echo twig_escape_filter($this->env, (isset($context["url"]) || array_key_exists("url", $context) ? $context["url"] : (function () { throw new RuntimeError('Variable "url" does not exist.', 1, $this->source); })()), "html", null, true);
        echo "\">
    ";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 2, $this->source); })()), "html", null, true);
        echo "
    ";
        // line 3
        if ((isset($context["icon"]) || array_key_exists("icon", $context) ? $context["icon"] : (function () { throw new RuntimeError('Variable "icon" does not exist.', 3, $this->source); })())) {
            // line 4
            if (((isset($context["currentOrder"]) || array_key_exists("currentOrder", $context) ? $context["currentOrder"] : (function () { throw new RuntimeError('Variable "currentOrder" does not exist.', 4, $this->source); })()) == "desc")) {
                // line 5
                echo "<i class=\"glyphicon glyphicon-chevron-down\"></i>";
            } else {
                // line 7
                echo "<i class=\"glyphicon glyphicon-chevron-up\"></i>";
            }
        }
        // line 10
        echo "</a>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@SyliusResource/Twig/sorting.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 10,  56 => 7,  53 => 5,  51 => 4,  49 => 3,  45 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<a href=\"{{ url }}\">
    {{ label }}
    {% if icon -%}
        {%- if currentOrder == 'desc' -%}
            <i class=\"glyphicon glyphicon-chevron-down\"></i>
        {%- else -%}
            <i class=\"glyphicon glyphicon-chevron-up\"></i>
        {%- endif %}
    {%- endif %}
</a>
", "@SyliusResource/Twig/sorting.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/src/Bundle/Resources/views/Twig/sorting.html.twig");
    }
}
