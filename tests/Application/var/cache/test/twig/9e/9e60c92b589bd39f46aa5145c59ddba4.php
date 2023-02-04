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

/* @JMSSerializer/Collector/events.html.twig */
class __TwigTemplate_cd8a5024830e11cda210f4aa78ca776e extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@JMSSerializer/Collector/events.html.twig"));

        // line 1
        $macros["helper"] = $this->macros["helper"] = $this;
        // line 3
        echo "<h2>Event Dispatcher</h2>

<div class=\"sf-tabs\">
    <div class=\"tab\">
        <div class=\"tab-title\">Triggered Listeners <span class=\"badge\">";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 7, $this->source); })()), "getNumListeners", [0 => "called"], "method", false, false, false, 7), "html", null, true);
        echo "</span></div>

        <div class=\"tab-content\">";
        // line 10
        if ((0 == twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 10, $this->source); })()), "getNumListeners", [0 => "called"], "method", false, false, false, 10))) {
            // line 11
            echo "<div class=\"empty\">
                    <p>No triggered listeners.</p>
                </div>";
        } else {
            // line 15
            echo twig_call_macro($macros["helper"], "macro_render_table", [twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 15, $this->source); })()), "triggeredListeners", [], "any", false, false, false, 15)], 15, $context, $this->getSourceContext());
        }
        // line 17
        echo "</div>
    </div>

    <div class=\"tab\">
        <div class=\"tab-title\">Not Called Listeners <span class=\"badge\">";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 21, $this->source); })()), "getNumListeners", [0 => "not_called"], "method", false, false, false, 21), "html", null, true);
        echo "</span></div>
        <div class=\"tab-content\">
            ";
        // line 23
        echo twig_call_macro($macros["helper"], "macro_render_table_not_triggered_listeners", [twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 23, $this->source); })()), "notTriggeredListeners", [], "any", false, false, false, 23)], 23, $context, $this->getSourceContext());
        echo "
        </div>
    </div>
</div>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 28
    public function macro_render_table_not_triggered_listeners($__notCalledListenersPerEvent__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "notCalledListenersPerEvent" => $__notCalledListenersPerEvent__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "render_table_not_triggered_listeners"));

            // line 30
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["notCalledListenersPerEvent"]) || array_key_exists("notCalledListenersPerEvent", $context) ? $context["notCalledListenersPerEvent"] : (function () { throw new RuntimeError('Variable "notCalledListenersPerEvent" does not exist.', 30, $this->source); })()));
            foreach ($context['_seq'] as $context["eventName"] => $context["listeners"]) {
                // line 31
                echo "<h3>";
                echo twig_escape_filter($this->env, $context["eventName"], "html", null, true);
                echo "</h3>
            <table>
                <thead>
                    <tr>
                        <th colspan=\"2\">Listener</th>
                    </tr>
                </thead>";
                // line 38
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["listeners"]);
                foreach ($context['_seq'] as $context["_key"] => $context["listener"]) {
                    // line 39
                    echo "<tr>
                        <th class=\"colored font-normal\" colspan=\"4\">";
                    // line 40
                    echo twig_var_dump($this->env, $context, ...[0 => $context["listener"]]);
                    echo "</th>
                    </tr>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['listener'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 43
                echo "</table>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['eventName'], $context['listeners'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 48
    public function macro_render_table($__listeners__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "listeners" => $__listeners__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "render_table"));

            // line 50
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["listeners"]) || array_key_exists("listeners", $context) ? $context["listeners"] : (function () { throw new RuntimeError('Variable "listeners" does not exist.', 50, $this->source); })()));
            foreach ($context['_seq'] as $context["eventName"] => $context["callsPerlistener"]) {
                // line 51
                echo "<h3>";
                echo twig_escape_filter($this->env, $context["eventName"], "html", null, true);
                echo "</h3>
        <table>
            <thead>
            <tr>
                <th colspan=\"2\">Class</th>
                <th class=\"text-right\">Calls</th>
                <th class=\"text-right\">Total duration (ms)</th>
            </tr>
            </thead>";
                // line 60
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["callsPerlistener"]);
                foreach ($context['_seq'] as $context["listener"] => $context["callsPerClass"]) {
                    // line 61
                    echo "<tr>
                    <th class=\"colored font-normal\" colspan=\"4\">";
                    // line 62
                    echo twig_var_dump($this->env, $context, ...[0 => $context["listener"]]);
                    echo "</th>
                </tr>";
                    // line 64
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["callsPerClass"]);
                    foreach ($context['_seq'] as $context["className"] => $context["callsInfo"]) {
                        // line 65
                        echo "<tr>
                        <td>&nbsp;</td>
                        <td>";
                        // line 67
                        echo twig_escape_filter($this->env, $context["className"], "html", null, true);
                        echo "</td>
                        <td class=\"text-right\">";
                        // line 68
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["callsInfo"], "calls", [], "any", false, false, false, 68), "html", null, true);
                        echo "</td>
                        <td class=\"text-right\">";
                        // line 69
                        ((twig_get_attribute($this->env, $this->source, $context["callsInfo"], "duration", [], "any", false, false, false, 69)) ? (print (twig_escape_filter($this->env, twig_number_format_filter($this->env, (twig_get_attribute($this->env, $this->source, $context["callsInfo"], "duration", [], "any", false, false, false, 69) * 1000), 4), "html", null, true))) : (print ("")));
                        echo "</td>
                    </tr>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['className'], $context['callsInfo'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['listener'], $context['callsPerClass'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 73
                echo "</table>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['eventName'], $context['callsPerlistener'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@JMSSerializer/Collector/events.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  217 => 73,  205 => 69,  201 => 68,  197 => 67,  193 => 65,  189 => 64,  185 => 62,  182 => 61,  178 => 60,  166 => 51,  162 => 50,  146 => 48,  130 => 43,  122 => 40,  119 => 39,  115 => 38,  105 => 31,  101 => 30,  85 => 28,  74 => 23,  69 => 21,  63 => 17,  60 => 15,  55 => 11,  53 => 10,  48 => 7,  42 => 3,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{%- import _self as helper -%}

<h2>Event Dispatcher</h2>

<div class=\"sf-tabs\">
    <div class=\"tab\">
        <div class=\"tab-title\">Triggered Listeners <span class=\"badge\">{{ collector.getNumListeners('called') }}</span></div>

        <div class=\"tab-content\">
            {%- if 0 == collector.getNumListeners('called') -%}
                <div class=\"empty\">
                    <p>No triggered listeners.</p>
                </div>
            {%- else -%}
                {{- helper.render_table(collector.triggeredListeners) -}}
            {%- endif -%}
        </div>
    </div>

    <div class=\"tab\">
        <div class=\"tab-title\">Not Called Listeners <span class=\"badge\">{{ collector.getNumListeners('not_called') }}</span></div>
        <div class=\"tab-content\">
            {{ helper.render_table_not_triggered_listeners(collector.notTriggeredListeners) }}
        </div>
    </div>
</div>

{%- macro render_table_not_triggered_listeners(notCalledListenersPerEvent) -%}

    {%- for eventName, listeners in notCalledListenersPerEvent -%}
        <h3>{{ eventName }}</h3>
            <table>
                <thead>
                    <tr>
                        <th colspan=\"2\">Listener</th>
                    </tr>
                </thead>
                {%- for listener in listeners -%}
                    <tr>
                        <th class=\"colored font-normal\" colspan=\"4\">{{ dump(listener) }}</th>
                    </tr>
                {%- endfor -%}
            </table>
    {%- endfor -%}
{%- endmacro -%}


{%- macro render_table(listeners) -%}

    {%- for eventName, callsPerlistener in listeners -%}
        <h3>{{ eventName }}</h3>
        <table>
            <thead>
            <tr>
                <th colspan=\"2\">Class</th>
                <th class=\"text-right\">Calls</th>
                <th class=\"text-right\">Total duration (ms)</th>
            </tr>
            </thead>
            {%- for listener, callsPerClass in callsPerlistener -%}
                <tr>
                    <th class=\"colored font-normal\" colspan=\"4\">{{ dump(listener) }}</th>
                </tr>
                {%- for className, callsInfo in callsPerClass -%}
                    <tr>
                        <td>&nbsp;</td>
                        <td>{{ className }}</td>
                        <td class=\"text-right\">{{ callsInfo.calls }}</td>
                        <td class=\"text-right\">{{ callsInfo.duration ? (callsInfo.duration * 1000)|number_format(4) : '' }}</td>
                    </tr>
                {%- endfor -%}
            {%- endfor -%}
        </table>
    {%- endfor -%}
{%- endmacro -%}
", "@JMSSerializer/Collector/events.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/jms/serializer-bundle/Resources/views/Collector/events.html.twig");
    }
}
