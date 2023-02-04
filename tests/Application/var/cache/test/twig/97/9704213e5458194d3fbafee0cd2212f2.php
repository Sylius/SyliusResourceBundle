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

/* @JMSSerializer/Collector/handlers.html.twig */
class __TwigTemplate_8fcce3a321345ae0366c04b7dfa136aa extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@JMSSerializer/Collector/handlers.html.twig"));

        // line 1
        $macros["helper"] = $this->macros["helper"] = $this;
        // line 3
        echo "<h2>Type Handlers</h2>

<div class=\"sf-tabs\">
    <div class=\"tab\">
        <h3 class=\"tab-title\">Triggered Handlers <span class=\"badge\">";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 7, $this->source); })()), "getNumHandlers", [0 => "called"], "method", false, false, false, 7), "html", null, true);
        echo "</span></h3>

        <div class=\"tab-content\">";
        // line 10
        if ((0 == twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 10, $this->source); })()), "getNumHandlers", [0 => "called"], "method", false, false, false, 10))) {
            // line 11
            echo "<div class=\"empty\">
                    <p>No triggered handlers.</p>
                </div>";
        } else {
            // line 15
            echo twig_call_macro($macros["helper"], "macro_render_table_triggered_handlers", [twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 15, $this->source); })()), "triggeredHandlers", [], "any", false, false, false, 15)], 15, $context, $this->getSourceContext());
        }
        // line 17
        echo "</div>
    </div>

    <div class=\"tab\">
        <div class=\"tab-title\">Not Triggered Handlers <span class=\"badge\">";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 21, $this->source); })()), "getNumHandlers", [0 => "not_called"], "method", false, false, false, 21), "html", null, true);
        echo "</span></div>
        <div class=\"tab-content\">
            ";
        // line 23
        echo twig_call_macro($macros["helper"], "macro_render_not_table_triggered_handlers", [twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 23, $this->source); })()), "notTriggeredHandlers", [], "any", false, false, false, 23)], 23, $context, $this->getSourceContext());
        echo "
        </div>
    </div>
</div>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 28
    public function macro_render_not_table_triggered_handlers($__handlers__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "handlers" => $__handlers__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "render_not_table_triggered_handlers"));

            // line 29
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["handlers"]) || array_key_exists("handlers", $context) ? $context["handlers"] : (function () { throw new RuntimeError('Variable "handlers" does not exist.', 29, $this->source); })()));
            foreach ($context['_seq'] as $context["direction"] => $context["callsByType"]) {
                // line 30
                echo "<h3>";
                // line 31
                if (($context["direction"] == twig_constant("JMS\\Serializer\\GraphNavigatorInterface::DIRECTION_SERIALIZATION"))) {
                    // line 32
                    echo "Serialization";
                } else {
                    // line 34
                    echo "Deserialization";
                }
                // line 36
                echo "</h3>
        <table>
            <thead>
            <tr>
                <th>Date type</th>
                <th>Handler</th>";
                // line 42
                if (((array_key_exists("called", $context)) ? (_twig_default_filter((isset($context["called"]) || array_key_exists("called", $context) ? $context["called"] : (function () { throw new RuntimeError('Variable "called" does not exist.', 42, $this->source); })()), false)) : (false))) {
                    // line 43
                    echo "<th>Calls</th>
                    <th>Total duration (ms)</th>";
                }
                // line 46
                echo "</tr>
            </thead>";
                // line 48
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["callsByType"]);
                foreach ($context['_seq'] as $context["type"] => $context["handlers"]) {
                    // line 49
                    echo "<tr>
                    <td>";
                    // line 50
                    echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                    echo "</td>
                    <td>";
                    // line 52
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["handlers"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["handler"]) {
                        // line 53
                        echo twig_var_dump($this->env, $context, ...[0 => $context["handler"]]);
                        echo "<br>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['handler'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 55
                    echo "</td>
                </tr>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['type'], $context['handlers'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 59
                echo "</table>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['direction'], $context['callsByType'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 63
    public function macro_render_table_triggered_handlers($__handlers__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "handlers" => $__handlers__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "render_table_triggered_handlers"));

            // line 64
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["handlers"]) || array_key_exists("handlers", $context) ? $context["handlers"] : (function () { throw new RuntimeError('Variable "handlers" does not exist.', 64, $this->source); })()));
            foreach ($context['_seq'] as $context["direction"] => $context["callsByType"]) {
                // line 65
                echo "<h3>";
                // line 66
                if (($context["direction"] == twig_constant("JMS\\Serializer\\GraphNavigatorInterface::DIRECTION_SERIALIZATION"))) {
                    // line 67
                    echo "Serialization";
                } else {
                    // line 69
                    echo "Deserialization";
                }
                // line 71
                echo "</h3>
        <table>
            <thead>
            <tr>
                <th>Date type</th>
                <th>Handler</th>
                <th>Calls</th>
                <th>Total duration (ms)</th>
            </tr>
            </thead>";
                // line 81
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["callsByType"]);
                foreach ($context['_seq'] as $context["type"] => $context["calls"]) {
                    // line 82
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["calls"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["call"]) {
                        // line 83
                        echo "<tr>
                        <td>";
                        // line 84
                        echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                        echo "</td>
                        <td>";
                        // line 85
                        echo twig_var_dump($this->env, $context, ...[0 => twig_get_attribute($this->env, $this->source, $context["call"], "handler", [], "any", false, false, false, 85)]);
                        echo "</td>

                        <td class=\"text-right\">";
                        // line 87
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["call"], "calls", [], "any", false, false, false, 87), "html", null, true);
                        echo "</td>
                        <td class=\"text-right\">";
                        // line 88
                        ((twig_get_attribute($this->env, $this->source, $context["call"], "duration", [], "any", false, false, false, 88)) ? (print (twig_escape_filter($this->env, twig_number_format_filter($this->env, (twig_get_attribute($this->env, $this->source, $context["call"], "duration", [], "any", false, false, false, 88) * 1000), 4), "html", null, true))) : (print ("")));
                        echo "</td>
                    </tr>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['call'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['type'], $context['calls'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 93
                echo "</table>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['direction'], $context['callsByType'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@JMSSerializer/Collector/handlers.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  253 => 93,  241 => 88,  237 => 87,  232 => 85,  228 => 84,  225 => 83,  221 => 82,  217 => 81,  206 => 71,  203 => 69,  200 => 67,  198 => 66,  196 => 65,  192 => 64,  176 => 63,  160 => 59,  153 => 55,  146 => 53,  142 => 52,  138 => 50,  135 => 49,  131 => 48,  128 => 46,  124 => 43,  122 => 42,  115 => 36,  112 => 34,  109 => 32,  107 => 31,  105 => 30,  101 => 29,  85 => 28,  74 => 23,  69 => 21,  63 => 17,  60 => 15,  55 => 11,  53 => 10,  48 => 7,  42 => 3,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{%- import _self as helper -%}

<h2>Type Handlers</h2>

<div class=\"sf-tabs\">
    <div class=\"tab\">
        <h3 class=\"tab-title\">Triggered Handlers <span class=\"badge\">{{ collector.getNumHandlers('called') }}</span></h3>

        <div class=\"tab-content\">
            {%- if 0 == collector.getNumHandlers('called') -%}
                <div class=\"empty\">
                    <p>No triggered handlers.</p>
                </div>
            {%- else -%}
                {{- helper.render_table_triggered_handlers(collector.triggeredHandlers) -}}
            {%- endif -%}
        </div>
    </div>

    <div class=\"tab\">
        <div class=\"tab-title\">Not Triggered Handlers <span class=\"badge\">{{ collector.getNumHandlers('not_called') }}</span></div>
        <div class=\"tab-content\">
            {{ helper.render_not_table_triggered_handlers(collector.notTriggeredHandlers) }}
        </div>
    </div>
</div>

{%- macro render_not_table_triggered_handlers(handlers) -%}
    {%- for direction, callsByType in handlers -%}
        <h3>
            {%- if direction == constant('JMS\\\\Serializer\\\\GraphNavigatorInterface::DIRECTION_SERIALIZATION') -%}
                Serialization
            {%- else -%}
                Deserialization
            {%- endif -%}
        </h3>
        <table>
            <thead>
            <tr>
                <th>Date type</th>
                <th>Handler</th>
                {%- if called|default(false) -%}
                    <th>Calls</th>
                    <th>Total duration (ms)</th>
                {%- endif -%}
            </tr>
            </thead>
            {%- for type, handlers in callsByType -%}
                <tr>
                    <td>{{ type }}</td>
                    <td>
                        {%- for handler in handlers -%}
                            {{ dump(handler) }}<br>
                        {%- endfor -%}
                    </td>
                </tr>
            {%- endfor -%}

        </table>
    {%- endfor -%}
{%- endmacro -%}

{%- macro render_table_triggered_handlers(handlers) -%}
    {%- for direction, callsByType in handlers -%}
        <h3>
            {%- if direction == constant('JMS\\\\Serializer\\\\GraphNavigatorInterface::DIRECTION_SERIALIZATION') -%}
                Serialization
            {%- else -%}
                Deserialization
            {%- endif -%}
        </h3>
        <table>
            <thead>
            <tr>
                <th>Date type</th>
                <th>Handler</th>
                <th>Calls</th>
                <th>Total duration (ms)</th>
            </tr>
            </thead>
            {%- for type, calls in callsByType -%}
                {%- for call in calls -%}
                    <tr>
                        <td>{{ type }}</td>
                        <td>{{ dump(call.handler) }}</td>

                        <td class=\"text-right\">{{ call.calls }}</td>
                        <td class=\"text-right\">{{ call.duration ?  (call.duration * 1000)|number_format(4) : '' }}</td>
                    </tr>
                {%- endfor -%}
            {%- endfor -%}

        </table>
    {%- endfor -%}
{%- endmacro -%}
", "@JMSSerializer/Collector/handlers.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/jms/serializer-bundle/Resources/views/Collector/handlers.html.twig");
    }
}
