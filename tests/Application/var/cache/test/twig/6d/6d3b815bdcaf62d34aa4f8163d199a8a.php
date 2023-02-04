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

/* @SyliusResource/forms.html.twig */
class __TwigTemplate_e0d01c06d41c072bc2b1fed3ccbd281a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'collection_widget' => [$this, 'block_collection_widget'],
        ];
        $macros["_self"] = $this->macros["_self"] = $this;
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@SyliusResource/forms.html.twig"));

        // line 1
        $this->displayBlock('collection_widget', $context, $blocks);
        // line 39
        echo "
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 1
    public function block_collection_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "collection_widget"));

        // line 2
        $macros["__internal_parse_0"] = $this->loadTemplate("@SyliusResource/Macros/notification.html.twig", "@SyliusResource/forms.html.twig", 2)->unwrap();
        // line 3
        echo "    ";
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 3, $this->source); })()), ["class" => (((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 3)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 3))) : ("")) . " controls collection-widget")]);
        // line 4
        echo "
    ";
        // line 5
        ob_start();
        // line 6
        echo "        <div data-form-type=\"collection\"
             ";
        // line 7
        $this->displayBlock("widget_container_attributes", $context, $blocks);
        echo "
             ";
        // line 8
        if ((array_key_exists("prototype", $context) && (isset($context["allow_add"]) || array_key_exists("allow_add", $context) ? $context["allow_add"] : (function () { throw new RuntimeError('Variable "allow_add" does not exist.', 8, $this->source); })()))) {
            // line 9
            echo "             data-prototype='";
            echo twig_escape_filter($this->env, twig_call_macro($macros["_self"], "macro_collectionItem", [(isset($context["prototype"]) || array_key_exists("prototype", $context) ? $context["prototype"] : (function () { throw new RuntimeError('Variable "prototype" does not exist.', 9, $this->source); })()), (isset($context["allow_delete"]) || array_key_exists("allow_delete", $context) ? $context["allow_delete"] : (function () { throw new RuntimeError('Variable "allow_delete" does not exist.', 9, $this->source); })()), (isset($context["button_delete_label"]) || array_key_exists("button_delete_label", $context) ? $context["button_delete_label"] : (function () { throw new RuntimeError('Variable "button_delete_label" does not exist.', 9, $this->source); })()), "__name__"], 9, $context, $this->getSourceContext()));
            echo "'";
        }
        // line 10
        echo ">

            ";
        // line 12
        echo twig_call_macro($macros["__internal_parse_0"], "macro_error", [twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 12, $this->source); })()), "vars", [], "any", false, false, false, 12), "errors", [], "any", false, false, false, 12)], 12, $context, $this->getSourceContext());
        echo "

            ";
        // line 14
        if (twig_test_iterable(((array_key_exists("prototypes", $context)) ? (_twig_default_filter((isset($context["prototypes"]) || array_key_exists("prototypes", $context) ? $context["prototypes"] : (function () { throw new RuntimeError('Variable "prototypes" does not exist.', 14, $this->source); })()))) : ("")))) {
            // line 15
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["prototypes"]) || array_key_exists("prototypes", $context) ? $context["prototypes"] : (function () { throw new RuntimeError('Variable "prototypes" does not exist.', 15, $this->source); })()));
            foreach ($context['_seq'] as $context["key"] => $context["subPrototype"]) {
                // line 16
                echo "                    <input type=\"hidden\"
                           data-form-prototype=\"";
                // line 17
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "\"
                           value=\"";
                // line 18
                echo twig_escape_filter($this->env, twig_call_macro($macros["_self"], "macro_collectionItem", [$context["subPrototype"], (isset($context["allow_delete"]) || array_key_exists("allow_delete", $context) ? $context["allow_delete"] : (function () { throw new RuntimeError('Variable "allow_delete" does not exist.', 18, $this->source); })()), (isset($context["button_delete_label"]) || array_key_exists("button_delete_label", $context) ? $context["button_delete_label"] : (function () { throw new RuntimeError('Variable "button_delete_label" does not exist.', 18, $this->source); })()), "__name__"], 18, $context, $this->getSourceContext()));
                echo "\" />
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['subPrototype'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 20
            echo "            ";
        }
        // line 21
        echo "
            <div data-form-collection=\"list\"
                 class=\"row collection-list\">
                ";
        // line 24
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 24, $this->source); })()));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 25
            echo "                    ";
            echo twig_call_macro($macros["_self"], "macro_collectionItem", [$context["child"], (isset($context["allow_delete"]) || array_key_exists("allow_delete", $context) ? $context["allow_delete"] : (function () { throw new RuntimeError('Variable "allow_delete" does not exist.', 25, $this->source); })()), (isset($context["button_delete_label"]) || array_key_exists("button_delete_label", $context) ? $context["button_delete_label"] : (function () { throw new RuntimeError('Variable "button_delete_label" does not exist.', 25, $this->source); })()), twig_get_attribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 25)], 25, $context, $this->getSourceContext());
            echo "
                ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "            </div>

            ";
        // line 29
        if ((array_key_exists("prototype", $context) && (isset($context["allow_add"]) || array_key_exists("allow_add", $context) ? $context["allow_add"] : (function () { throw new RuntimeError('Variable "allow_add" does not exist.', 29, $this->source); })()))) {
            // line 30
            echo "                <a href=\"#\" class=\"btn btn-success btn-block\"
                   data-form-collection=\"add\">
                    <i class=\"glyphicon glyphicon-plus\"></i>
                    ";
            // line 33
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans((isset($context["button_add_label"]) || array_key_exists("button_add_label", $context) ? $context["button_add_label"] : (function () { throw new RuntimeError('Variable "button_add_label" does not exist.', 33, $this->source); })())), "html", null, true);
            echo "
                </a>
            ";
        }
        // line 36
        echo "        </div>
    ";
        $___internal_parse_1_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 5
        echo twig_spaceless($___internal_parse_1_);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 40
    public function macro_collectionItem($__form__ = null, $__allow_delete__ = null, $__button_delete_label__ = null, $__index__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "form" => $__form__,
            "allow_delete" => $__allow_delete__,
            "button_delete_label" => $__button_delete_label__,
            "index" => $__index__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "collectionItem"));

            // line 41
            echo "    ";
            ob_start();
            // line 42
            echo "        <div data-form-collection=\"item\"
             data-form-collection-index=\"";
            // line 43
            echo twig_escape_filter($this->env, (isset($context["index"]) || array_key_exists("index", $context) ? $context["index"] : (function () { throw new RuntimeError('Variable "index" does not exist.', 43, $this->source); })()), "html", null, true);
            echo "\"
             class=\"collection-item\">
            <div class=\"collection-box";
            // line 45
            if ((twig_length_filter($this->env, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 45, $this->source); })())) == 1)) {
                echo " unique-field";
            }
            echo "\">
                ";
            // line 46
            if ((isset($context["allow_delete"]) || array_key_exists("allow_delete", $context) ? $context["allow_delete"] : (function () { throw new RuntimeError('Variable "allow_delete" does not exist.', 46, $this->source); })())) {
                // line 47
                echo "                    <p class=\"text-right\">
                        <a href=\"#\" data-form-collection=\"delete\" class=\"btn btn-danger\">
                            <i class=\"glyphicon glyphicon-remove collection-button-remove\"></i>
                            ";
                // line 50
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans((isset($context["button_delete_label"]) || array_key_exists("button_delete_label", $context) ? $context["button_delete_label"] : (function () { throw new RuntimeError('Variable "button_delete_label" does not exist.', 50, $this->source); })())), "html", null, true);
                echo "
                        </a>
                    </p>
                ";
            }
            // line 54
            echo "                ";
            if ( !twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 54, $this->source); })()), "children", [], "any", false, false, false, 54))) {
                // line 55
                echo "                    ";
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 55, $this->source); })()), 'widget');
                echo "
                ";
            } else {
                // line 57
                echo "                    ";
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 57, $this->source); })()), 'rest');
                echo "
                ";
            }
            // line 59
            echo "            </div>
        </div>
    ";
            $___internal_parse_2_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 41
            echo twig_spaceless($___internal_parse_2_);
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@SyliusResource/forms.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  254 => 41,  249 => 59,  243 => 57,  237 => 55,  234 => 54,  227 => 50,  222 => 47,  220 => 46,  214 => 45,  209 => 43,  206 => 42,  203 => 41,  184 => 40,  177 => 5,  173 => 36,  167 => 33,  162 => 30,  160 => 29,  156 => 27,  139 => 25,  122 => 24,  117 => 21,  114 => 20,  106 => 18,  102 => 17,  99 => 16,  94 => 15,  92 => 14,  87 => 12,  83 => 10,  78 => 9,  76 => 8,  72 => 7,  69 => 6,  67 => 5,  64 => 4,  61 => 3,  59 => 2,  52 => 1,  44 => 39,  42 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% block collection_widget -%}
    {% from '@SyliusResource/Macros/notification.html.twig' import error %}
    {% set attr = attr|merge({'class': attr.class|default ~ ' controls collection-widget'}) %}

    {% apply spaceless %}
        <div data-form-type=\"collection\"
             {{ block('widget_container_attributes') }}
             {% if prototype is defined and allow_add %}
             data-prototype='{{ _self.collectionItem(prototype, allow_delete, button_delete_label, '__name__')|e }}'
             {%- endif -%}>

            {{ error(form.vars.errors) }}

            {% if prototypes|default is iterable %}
                {% for key, subPrototype in prototypes %}
                    <input type=\"hidden\"
                           data-form-prototype=\"{{ key }}\"
                           value=\"{{ _self.collectionItem(subPrototype, allow_delete, button_delete_label, '__name__')|e }}\" />
                {% endfor %}
            {% endif %}

            <div data-form-collection=\"list\"
                 class=\"row collection-list\">
                {% for child in form %}
                    {{ _self.collectionItem(child, allow_delete, button_delete_label, loop.index0) }}
                {% endfor %}
            </div>

            {% if prototype is defined and allow_add %}
                <a href=\"#\" class=\"btn btn-success btn-block\"
                   data-form-collection=\"add\">
                    <i class=\"glyphicon glyphicon-plus\"></i>
                    {{ button_add_label|trans }}
                </a>
            {% endif %}
        </div>
    {% endapply %}
{%- endblock collection_widget %}

{% macro collectionItem(form, allow_delete, button_delete_label, index) %}
    {% apply spaceless %}
        <div data-form-collection=\"item\"
             data-form-collection-index=\"{{ index }}\"
             class=\"collection-item\">
            <div class=\"collection-box{% if form|length == 1 %} unique-field{% endif %}\">
                {% if allow_delete %}
                    <p class=\"text-right\">
                        <a href=\"#\" data-form-collection=\"delete\" class=\"btn btn-danger\">
                            <i class=\"glyphicon glyphicon-remove collection-button-remove\"></i>
                            {{ button_delete_label|trans }}
                        </a>
                    </p>
                {% endif %}
                {% if not form.children|length %}
                    {{ form_widget(form) }}
                {% else %}
                    {{ form_rest(form) }}
                {% endif %}
            </div>
        </div>
    {% endapply %}
{% endmacro %}
", "@SyliusResource/forms.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/src/Bundle/Resources/views/forms.html.twig");
    }
}
