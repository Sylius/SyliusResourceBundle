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

/* @JMSSerializer/Collector/panel.html.twig */
class __TwigTemplate_1c9dc5df5457091a0febc90b598423d9 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'toolbar' => [$this, 'block_toolbar'],
            'head' => [$this, 'block_head'],
            'menu' => [$this, 'block_menu'],
            'panel' => [$this, 'block_panel'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@JMSSerializer/Collector/panel.html.twig"));

        $this->parent = $this->loadTemplate("@WebProfiler/Profiler/layout.html.twig", "@JMSSerializer/Collector/panel.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    public function block_toolbar($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "toolbar"));

        // line 5
        ob_start();
        // line 6
        echo twig_include($this->env, $context, "@JMSSerializer/icon.svg");
        echo "
        <span class=\"sf-toolbar-label\">S:</span>
        <span class=\"sf-toolbar-value\">";
        // line 8
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 8, $this->source); })()), "runs", [0 => 1], "method", false, false, false, 8)), "html", null, true);
        echo "</span>
        <span class=\"sf-toolbar-label\">D:</span>
        <span class=\"sf-toolbar-value\">";
        // line 10
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 10, $this->source); })()), "runs", [0 => 2], "method", false, false, false, 10)), "html", null, true);
        echo "</span>";
        $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 13
        ob_start();
        // line 14
        echo "<div class=\"sf-toolbar-info-piece\">
            <b>Serializations</b>
            <span class=\"sf-toolbar-status\">";
        // line 16
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 16, $this->source); })()), "runs", [0 => 1], "method", false, false, false, 16)), "html", null, true);
        echo "</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Deserializations</b>
            <span class=\"sf-toolbar-status\">";
        // line 20
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 20, $this->source); })()), "runs", [0 => 2], "method", false, false, false, 20)), "html", null, true);
        echo "</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Listeners</b>
            <span class=\"sf-toolbar-status\">";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 24, $this->source); })()), "getNumListeners", [0 => "called"], "method", false, false, false, 24), "html", null, true);
        echo "</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Handlers</b>
            <span class=\"sf-toolbar-status\">";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 28, $this->source); })()), "getNumHandlers", [0 => "called"], "method", false, false, false, 28), "html", null, true);
        echo "</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Metadata</b>
            <span class=\"sf-toolbar-status ";
        // line 32
        echo (((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 32, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 32)) > 0)) ? ("sf-toolbar-status-none") : (""));
        echo "\">";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 32, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 32)), "html", null, true);
        echo "</span>
        </div>";
        $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 36
        $this->loadTemplate("@WebProfiler/Profiler/toolbar_item.html.twig", "@JMSSerializer/Collector/panel.html.twig", 36)->display(twig_array_merge($context, ["link" => (isset($context["profiler_url"]) || array_key_exists("profiler_url", $context) ? $context["profiler_url"] : (function () { throw new RuntimeError('Variable "profiler_url" does not exist.', 36, $this->source); })())]));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 39
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "head"));

        // line 40
        echo "<style>
        ";
        // line 41
        echo twig_include($this->env, $context, "@JMSSerializer/Collector/style/jms.css.twig");
        echo "
    </style>
    <style>
        .margin-left {
            margin-left: 10px;
        }

        .margin-top {
            margin-top: 10px;
        }

        .margin-bottom {
            margin-bottom: 10px;
        }
    </style>
    <script type=\"text/javascript\">
        ";
        // line 57
        echo twig_include($this->env, $context, "@JMSSerializer/Collector/script/jms.js.twig");
        echo "
    </script>
    ";
        // line 59
        $this->displayParentBlock("head", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 62
    public function block_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "menu"));

        // line 64
        echo "    ";
        $context["total"] = (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 64, $this->source); })()), "runs", [0 => 1], "method", false, false, false, 64)) + twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 64, $this->source); })()), "runs", [0 => 2], "method", false, false, false, 64)));
        // line 65
        echo "    <span class=\"label ";
        echo ((((isset($context["total"]) || array_key_exists("total", $context) ? $context["total"] : (function () { throw new RuntimeError('Variable "total" does not exist.', 65, $this->source); })()) == 0)) ? ("disabled") : (""));
        echo "\">
        <span class=\"icon\">
            ";
        // line 67
        echo twig_include($this->env, $context, "@JMSSerializer/icon.svg");
        echo "
        </span>
        <strong>JMS Serializer</strong>

        <span class=\"count\">
            <span>";
        // line 72
        echo twig_escape_filter($this->env, (isset($context["total"]) || array_key_exists("total", $context) ? $context["total"] : (function () { throw new RuntimeError('Variable "total" does not exist.', 72, $this->source); })()), "html", null, true);
        echo "</span>
        </span>

    </span>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 78
    public function block_panel($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "panel"));

        // line 79
        echo "<h2>JMS Serializer</h2>

    <div class=\"metrics\">
        <div class=\"metric\">
            <span class=\"value\">";
        // line 83
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 83, $this->source); })()), "runs", [0 => 1], "method", false, false, false, 83)), "html", null, true);
        echo "</span>
            <span class=\"label\">Serializations</span>
        </div>
        <div class=\"metric\">
            <span class=\"value\">";
        // line 87
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 87, $this->source); })()), "runs", [0 => 2], "method", false, false, false, 87)), "html", null, true);
        echo "</span>
            <span class=\"label\">Deserializations</span>
        </div>
        <div class=\"metric-divider\"></div>
        <div class=\"metric\">
            <span class=\"value\">";
        // line 92
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 92, $this->source); })()), "triggeredEvents", [], "any", false, false, false, 92), "count", [], "any", false, false, false, 92), "html", null, true);
        echo "</span>
            <span class=\"label\">Triggered event listeners</span>
        </div>
        <div class=\"metric\">
            <span class=\"value\">";
        // line 96
        echo twig_escape_filter($this->env, twig_round(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 96, $this->source); })()), "triggeredEvents", [], "any", false, false, false, 96), "duration", [], "any", false, false, false, 96), 2), "html", null, true);
        echo " <span class=\"unit\">ms</span></span>
            <span class=\"label\">Triggered event listeners (time)</span>
        </div>
    </div>

    <div class=\"sf-tabs\">
        <div class=\"tab\">
            <h3 class=\"tab-title jms-toggle\" data-toggle=\"#jms-events\">
                Events
                <span class=\"badge\">
                    ";
        // line 106
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 106, $this->source); })()), "triggeredListeners", [], "any", false, false, false, 106)), "html", null, true);
        echo "
                    (";
        // line 107
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 107, $this->source); })()), "getNumListeners", [0 => "called"], "method", false, false, false, 107), "html", null, true);
        echo " listeners)
                </span>


            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Triggered event listeners</p>

                <div id=\"jms-events\">";
        // line 117
        $this->loadTemplate("@JMSSerializer/Collector/events.html.twig", "@JMSSerializer/Collector/panel.html.twig", 117)->display($context);
        // line 118
        echo "</div>
            </div>
        </div>

        <div class=\"tab\">
            <h3 class=\"tab-title jms-toggle\" data-toggle=\"#jms-handlers\">
                Handlers
                <span class=\"badge\">
                    ";
        // line 126
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 126, $this->source); })()), "triggeredHandlers", [], "any", false, false, false, 126)), "html", null, true);
        echo "
                    (";
        // line 127
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 127, $this->source); })()), "getNumHandlers", [0 => "called"], "method", false, false, false, 127), "html", null, true);
        echo " types)
                </span>
            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Triggered event handlers</p>

                <div id=\"jms-handlers\">";
        // line 135
        $this->loadTemplate("@JMSSerializer/Collector/handlers.html.twig", "@JMSSerializer/Collector/panel.html.twig", 135)->display($context);
        // line 136
        echo "</div>
            </div>
        </div>

        <div class=\"tab\">
            <h3 class=\"tab-title\">
                Metadata
                <span class=\"badge ";
        // line 143
        echo (((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 143, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 143)) > 0)) ? ("status-info") : (""));
        echo "\">";
        // line 144
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 144, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 144)), "html", null, true);
        // line 145
        echo "</span>
            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Loaded metadata</p>

                <div id=\"jms-metadata\">";
        // line 152
        $this->loadTemplate("@JMSSerializer/Collector/metadata.html.twig", "@JMSSerializer/Collector/panel.html.twig", 152)->display($context);
        // line 153
        echo "</div>
            </div>
        </div>
    </div>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@JMSSerializer/Collector/panel.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  318 => 153,  316 => 152,  308 => 145,  306 => 144,  303 => 143,  294 => 136,  292 => 135,  282 => 127,  278 => 126,  268 => 118,  266 => 117,  254 => 107,  250 => 106,  237 => 96,  230 => 92,  222 => 87,  215 => 83,  209 => 79,  202 => 78,  191 => 72,  183 => 67,  177 => 65,  174 => 64,  167 => 62,  160 => 59,  155 => 57,  136 => 41,  133 => 40,  126 => 39,  119 => 36,  112 => 32,  105 => 28,  98 => 24,  91 => 20,  84 => 16,  80 => 14,  78 => 13,  74 => 10,  69 => 8,  64 => 6,  62 => 5,  55 => 3,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{%- extends '@WebProfiler/Profiler/layout.html.twig' -%}

{%- block toolbar -%}

    {%- set icon -%}
        {{- include('@JMSSerializer/icon.svg') }}
        <span class=\"sf-toolbar-label\">S:</span>
        <span class=\"sf-toolbar-value\">{{- collector.runs(1)|length }}</span>
        <span class=\"sf-toolbar-label\">D:</span>
        <span class=\"sf-toolbar-value\">{{- collector.runs(2)|length }}</span>
    {%- endset -%}

    {%- set text -%}
        <div class=\"sf-toolbar-info-piece\">
            <b>Serializations</b>
            <span class=\"sf-toolbar-status\">{{ collector.runs(1)|length }}</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Deserializations</b>
            <span class=\"sf-toolbar-status\">{{ collector.runs(2)|length }}</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Listeners</b>
            <span class=\"sf-toolbar-status\">{{ collector.getNumListeners('called') }}</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Handlers</b>
            <span class=\"sf-toolbar-status\">{{ collector.getNumHandlers('called') }}</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Metadata</b>
            <span class=\"sf-toolbar-status {{ collector.loadedMetadata|length > 0 ? 'sf-toolbar-status-none' : '' }}\">{{ collector.loadedMetadata|length }}</span>
        </div>
    {%- endset -%}

    {%- include '@WebProfiler/Profiler/toolbar_item.html.twig' with { 'link': profiler_url } -%}
{%- endblock -%}

{%- block head -%}
    <style>
        {{ include('@JMSSerializer/Collector/style/jms.css.twig') }}
    </style>
    <style>
        .margin-left {
            margin-left: 10px;
        }

        .margin-top {
            margin-top: 10px;
        }

        .margin-bottom {
            margin-bottom: 10px;
        }
    </style>
    <script type=\"text/javascript\">
        {{ include('@JMSSerializer/Collector/script/jms.js.twig') }}
    </script>
    {{ parent() }}
{%- endblock -%}

{%- block menu -%}
    {# This left-hand menu appears when using the full-screen profiler. #}
    {% set total = (collector.runs(1)|length) + (collector.runs(2)|length) %}
    <span class=\"label {{ total == 0 ? 'disabled' : '' }}\">
        <span class=\"icon\">
            {{ include('@JMSSerializer/icon.svg') }}
        </span>
        <strong>JMS Serializer</strong>

        <span class=\"count\">
            <span>{{ total }}</span>
        </span>

    </span>
{%- endblock -%}

{%- block panel -%}
    <h2>JMS Serializer</h2>

    <div class=\"metrics\">
        <div class=\"metric\">
            <span class=\"value\">{{- collector.runs(1)|length }}</span>
            <span class=\"label\">Serializations</span>
        </div>
        <div class=\"metric\">
            <span class=\"value\">{{- collector.runs(2)|length }}</span>
            <span class=\"label\">Deserializations</span>
        </div>
        <div class=\"metric-divider\"></div>
        <div class=\"metric\">
            <span class=\"value\">{{- collector.triggeredEvents.count }}</span>
            <span class=\"label\">Triggered event listeners</span>
        </div>
        <div class=\"metric\">
            <span class=\"value\">{{- collector.triggeredEvents.duration|round(2) }} <span class=\"unit\">ms</span></span>
            <span class=\"label\">Triggered event listeners (time)</span>
        </div>
    </div>

    <div class=\"sf-tabs\">
        <div class=\"tab\">
            <h3 class=\"tab-title jms-toggle\" data-toggle=\"#jms-events\">
                Events
                <span class=\"badge\">
                    {{ collector.triggeredListeners|length }}
                    ({{ collector.getNumListeners('called') }} listeners)
                </span>


            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Triggered event listeners</p>

                <div id=\"jms-events\">
                    {%- include '@JMSSerializer/Collector/events.html.twig' -%}
                </div>
            </div>
        </div>

        <div class=\"tab\">
            <h3 class=\"tab-title jms-toggle\" data-toggle=\"#jms-handlers\">
                Handlers
                <span class=\"badge\">
                    {{ collector.triggeredHandlers|length }}
                    ({{ collector.getNumHandlers('called') }} types)
                </span>
            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Triggered event handlers</p>

                <div id=\"jms-handlers\">
                    {%- include '@JMSSerializer/Collector/handlers.html.twig' -%}
                </div>
            </div>
        </div>

        <div class=\"tab\">
            <h3 class=\"tab-title\">
                Metadata
                <span class=\"badge {{ collector.loadedMetadata|length > 0 ? 'status-info' : '' }}\">
                    {{- collector.loadedMetadata|length -}}
                </span>
            </h3>

            <div class=\"tab-content\">
                <p class=\"help\">Loaded metadata</p>

                <div id=\"jms-metadata\">
                    {%- include '@JMSSerializer/Collector/metadata.html.twig' -%}
                </div>
            </div>
        </div>
    </div>
{%- endblock -%}
", "@JMSSerializer/Collector/panel.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/jms/serializer-bundle/Resources/views/Collector/panel.html.twig");
    }
}
