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

/* @SyliusResource/Macros/actions.html.twig */
class __TwigTemplate_63d430f5d837753089e8b85c339c7151 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@SyliusResource/Macros/actions.html.twig"));

        // line 7
        echo "
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 1
    public function macro_create($__url__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "url" => $__url__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "create"));

            // line 2
            echo "<div class=\"form-actions\">
    <button type=\"submit\" class=\"btn btn-primary btn-lg\"><i class=\"glyphicon glyphicon-ok\"></i> ";
            // line 3
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("sylius.ui.create"), "html", null, true);
            echo "</button>
    <a href=\"";
            // line 4
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 4, $this->source); })()), "request", [], "any", false, false, false, 4), "headers", [], "any", false, false, false, 4), "get", [0 => "referer", 1 => (isset($context["url"]) || array_key_exists("url", $context) ? $context["url"] : (function () { throw new RuntimeError('Variable "url" does not exist.', 4, $this->source); })())], "method", false, false, false, 4), "html", null, true);
            echo "\" class=\"btn btn-danger btn-lg\"><i class=\"glyphicon glyphicon-remove\"></i> ";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("sylius.ui.cancel"), "html", null, true);
            echo "</a>
</div>
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 8
    public function macro_update($__url__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "url" => $__url__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "update"));

            // line 9
            echo "<div class=\"form-actions\">
    <button type=\"submit\" class=\"btn btn-primary btn-lg\"><i class=\"glyphicon glyphicon-save\"></i> ";
            // line 10
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("sylius.ui.save_changes"), "html", null, true);
            echo "</button>
    <a href=\"";
            // line 11
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 11, $this->source); })()), "request", [], "any", false, false, false, 11), "headers", [], "any", false, false, false, 11), "get", [0 => "referer", 1 => (isset($context["url"]) || array_key_exists("url", $context) ? $context["url"] : (function () { throw new RuntimeError('Variable "url" does not exist.', 11, $this->source); })())], "method", false, false, false, 11), "html", null, true);
            echo "\" class=\"btn btn-danger btn-lg\"><i class=\"glyphicon glyphicon-remove\"></i> ";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("sylius.ui.cancel"), "html", null, true);
            echo "</a>
</div>
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@SyliusResource/Macros/actions.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 11,  107 => 10,  104 => 9,  88 => 8,  71 => 4,  67 => 3,  64 => 2,  48 => 1,  40 => 7,);
    }

    public function getSourceContext()
    {
        return new Source("{% macro create(url) %}
<div class=\"form-actions\">
    <button type=\"submit\" class=\"btn btn-primary btn-lg\"><i class=\"glyphicon glyphicon-ok\"></i> {{ 'sylius.ui.create'|trans }}</button>
    <a href=\"{{ app.request.headers.get('referer', url) }}\" class=\"btn btn-danger btn-lg\"><i class=\"glyphicon glyphicon-remove\"></i> {{ 'sylius.ui.cancel'|trans }}</a>
</div>
{% endmacro %}

{% macro update(url) %}
<div class=\"form-actions\">
    <button type=\"submit\" class=\"btn btn-primary btn-lg\"><i class=\"glyphicon glyphicon-save\"></i> {{ 'sylius.ui.save_changes'|trans }}</button>
    <a href=\"{{ app.request.headers.get('referer', url) }}\" class=\"btn btn-danger btn-lg\"><i class=\"glyphicon glyphicon-remove\"></i> {{ 'sylius.ui.cancel'|trans }}</a>
</div>
{% endmacro %}
", "@SyliusResource/Macros/actions.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/src/Bundle/Resources/views/Macros/actions.html.twig");
    }
}
