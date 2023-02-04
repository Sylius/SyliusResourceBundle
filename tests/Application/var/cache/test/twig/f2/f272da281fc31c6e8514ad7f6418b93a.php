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

/* @SyliusResource/Macros/notification.html.twig */
class __TwigTemplate_2fe4cba48d587d2170a84d7704477621 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@SyliusResource/Macros/notification.html.twig"));

        // line 13
        echo "

";
        // line 41
        echo "
";
        // line 46
        echo "
";
        // line 51
        echo "
";
        // line 56
        echo "
";
        // line 61
        echo "
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 2
    public function macro_flashes(...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "flashes"));

            // line 3
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 3, $this->source); })()), "session", [], "any", false, false, false, 3), "flashBag", [], "any", false, false, false, 3), "all", [], "any", false, false, false, 3));
            foreach ($context['_seq'] as $context["type"] => $context["flashes"]) {
                // line 4
                echo "        <div id=\"flashesMessage\" class=\"alert alert-";
                echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                echo "\">
            <ul class=\"list-unstyled\">
                ";
                // line 6
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["flashes"]);
                foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
                    // line 7
                    echo "                    <li>";
                    echo twig_escape_filter($this->env, $context["flash"], "html", null, true);
                    echo "</li>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['flash'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 9
                echo "            </ul>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['type'], $context['flashes'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 20
    public function macro_alert($__text__ = null, $__type__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "type" => $__type__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "alert"));

            // line 21
            echo "    ";
            $context["type"] = ("alert-" . ((array_key_exists("type", $context)) ? (_twig_default_filter((isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 21, $this->source); })()), "info")) : ("info")));
            // line 22
            echo "
    ";
            // line 23
            if (((array_key_exists("text", $context)) ? (_twig_default_filter((isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 23, $this->source); })()))) : (""))) {
                // line 24
                echo "    <div class=\"alert ";
                echo twig_escape_filter($this->env, (isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 24, $this->source); })()), "html", null, true);
                echo "\">
        ";
                // line 25
                if (twig_test_iterable((isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 25, $this->source); })()))) {
                    // line 26
                    echo "            <ul class=\"form-type-errors list-unstyled\">
                ";
                    // line 27
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable((isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 27, $this->source); })()));
                    foreach ($context['_seq'] as $context["_key"] => $context["line"]) {
                        // line 28
                        echo "                    <li>
                        <p class=\"text-";
                        // line 29
                        echo twig_escape_filter($this->env, (isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 29, $this->source); })()), "html", null, true);
                        echo "\">
                            ";
                        // line 30
                        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(twig_get_attribute($this->env, $this->source, $context["line"], "message", [], "any", false, false, false, 30), twig_get_attribute($this->env, $this->source, $context["line"], "messageParameters", [], "any", false, false, false, 30)), "html", null, true);
                        echo "
                        </p>
                    </li>
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['line'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 34
                    echo "            </ul>
        ";
                } else {
                    // line 36
                    echo "            ";
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans((isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 36, $this->source); })())), "html", null, true);
                    echo "
        ";
                }
                // line 38
                echo "    </div>
    ";
            }
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 42
    public function macro_danger($__text__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "danger"));

            // line 43
            echo "    ";
            $macros["__internal_parse_3"] = $this;
            // line 44
            echo "    ";
            echo twig_call_macro($macros["__internal_parse_3"], "macro_alert", [(isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 44, $this->source); })()), "danger"], 44, $context, $this->getSourceContext());
            echo "
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 47
    public function macro_error($__text__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "error"));

            // line 48
            echo "    ";
            $macros["__internal_parse_4"] = $this;
            // line 49
            echo "    ";
            echo twig_call_macro($macros["__internal_parse_4"], "macro_danger", [(isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 49, $this->source); })())], 49, $context, $this->getSourceContext());
            echo "
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 52
    public function macro_warning($__text__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "warning"));

            // line 53
            echo "    ";
            $macros["__internal_parse_5"] = $this;
            // line 54
            echo "    ";
            echo twig_call_macro($macros["__internal_parse_5"], "macro_alert", [(isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 54, $this->source); })()), "warning"], 54, $context, $this->getSourceContext());
            echo "
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 57
    public function macro_success($__text__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "success"));

            // line 58
            echo "    ";
            $macros["__internal_parse_6"] = $this;
            // line 59
            echo "    ";
            echo twig_call_macro($macros["__internal_parse_6"], "macro_alert", [(isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 59, $this->source); })()), "success"], 59, $context, $this->getSourceContext());
            echo "
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 62
    public function macro_info($__text__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "text" => $__text__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "info"));

            // line 63
            echo "    ";
            $macros["__internal_parse_7"] = $this;
            // line 64
            echo "    ";
            echo twig_call_macro($macros["__internal_parse_7"], "macro_alert", [(isset($context["text"]) || array_key_exists("text", $context) ? $context["text"] : (function () { throw new RuntimeError('Variable "text" does not exist.', 64, $this->source); })()), "info"], 64, $context, $this->getSourceContext());
            echo "
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@SyliusResource/Macros/notification.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  356 => 64,  353 => 63,  337 => 62,  322 => 59,  319 => 58,  303 => 57,  288 => 54,  285 => 53,  269 => 52,  254 => 49,  251 => 48,  235 => 47,  220 => 44,  217 => 43,  201 => 42,  187 => 38,  181 => 36,  177 => 34,  167 => 30,  163 => 29,  160 => 28,  156 => 27,  153 => 26,  151 => 25,  146 => 24,  144 => 23,  141 => 22,  138 => 21,  121 => 20,  103 => 9,  94 => 7,  90 => 6,  84 => 4,  79 => 3,  64 => 2,  56 => 61,  53 => 56,  50 => 51,  47 => 46,  44 => 41,  40 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("{# Display all the flashes messages #}
{% macro flashes() %}
    {% for type, flashes in app.session.flashBag.all %}
        <div id=\"flashesMessage\" class=\"alert alert-{{ type }}\">
            <ul class=\"list-unstyled\">
                {% for flash in flashes %}
                    <li>{{ flash }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endfor %}
{% endmacro %}


{#
    Display a bootstrap alert :
    - text : Message to print (string or array)
    - type : danger | warning | success | info (Default : info)
#}
{% macro alert(text, type) %}
    {% set type = 'alert-' ~ type|default('info') %}

    {% if text|default %}
    <div class=\"alert {{ type }}\">
        {% if text is iterable %}
            <ul class=\"form-type-errors list-unstyled\">
                {% for line in text %}
                    <li>
                        <p class=\"text-{{ type }}\">
                            {{ line.message|trans(line.messageParameters) }}
                        </p>
                    </li>
                {% endfor %}
            </ul>
        {% else %}
            {{ text|trans }}
        {% endif %}
    </div>
    {% endif %}
{% endmacro %}

{% macro danger(text) %}
    {% from _self import alert %}
    {{ alert(text, 'danger') }}
{% endmacro %}

{% macro error(text) %}
    {% from _self import danger %}
    {{ danger(text) }}
{% endmacro %}

{% macro warning(text) %}
    {% from _self import alert %}
    {{ alert(text, 'warning') }}
{% endmacro %}

{% macro success(text) %}
    {% from _self import alert %}
    {{ alert(text, 'success') }}
{% endmacro %}

{% macro info(text) %}
    {% from _self import alert %}
    {{ alert(text, 'info') }}
{% endmacro %}
", "@SyliusResource/Macros/notification.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/src/Bundle/Resources/views/Macros/notification.html.twig");
    }
}
