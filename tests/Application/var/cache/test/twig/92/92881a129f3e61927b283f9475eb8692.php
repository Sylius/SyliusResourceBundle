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

/* @JMSSerializer/Collector/metadata.html.twig */
class __TwigTemplate_721fc2acfc7302d3b1f1bc21a99f4260 extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@JMSSerializer/Collector/metadata.html.twig"));

        // line 1
        echo "<h2>Loaded metadata</h2>";
        // line 3
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 3, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 3))) {
            // line 4
            echo "<div class=\"empty\">
        <p>No metadata have been loaded.</p>
    </div>";
        } else {
            // line 9
            echo "<table>
        <thead>
        <tr>
            <th>Class</th>
            <th>File</th>
        </tr>
        </thead>";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 16, $this->source); })()), "loadedMetadata", [], "any", false, false, false, 16));
            foreach ($context['_seq'] as $context["class"] => $context["files"]) {
                // line 17
                echo "<tr>
                <td>";
                // line 18
                echo twig_escape_filter($this->env, $context["class"], "html", null, true);
                echo "</td>
                <td nowrap=\"nowrap\">";
                // line 20
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["files"]);
                foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                    // line 21
                    echo twig_escape_filter($this->env, $context["file"], "html", null, true);
                    echo "<br/>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 23
                echo "                </td>
            </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['class'], $context['files'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 26
            echo "</table>";
        }
        // line 30
        echo "<h3>Attempted files</h3>";
        // line 31
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 31, $this->source); })()), "metadataFiles", [], "any", false, false, false, 31))) {
            // line 32
            echo "<div class=\"empty\">
        <p>No metadata files attempted (if this list is empty, probably all the data are cached as expected)</p>
    </div>";
        } else {
            // line 36
            echo "<table>
        <thead>
        <tr>
            <th>Class</th>
            <th>Files</th>
        </tr>
        </thead>";
            // line 43
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 43, $this->source); })()), "metadataFiles", [], "any", false, false, false, 43));
            foreach ($context['_seq'] as $context["class"] => $context["files"]) {
                // line 44
                echo "<tr>
                <td>";
                // line 45
                echo twig_escape_filter($this->env, $context["class"], "html", null, true);
                echo "</td>
                <td nowrap=\"nowrap\">";
                // line 47
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["files"]);
                foreach ($context['_seq'] as $context["file"] => $context["found"]) {
                    // line 48
                    echo "<span style=\"color: ";
                    echo (($context["found"]) ? ("green") : ("red"));
                    echo "\">
                        ";
                    // line 49
                    echo twig_escape_filter($this->env, $context["file"], "html", null, true);
                    echo " (";
                    echo (($context["found"]) ? ("found") : ("not found"));
                    echo ")<br/>
                        </span>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['file'], $context['found'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 52
                echo "                </td>
            </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['class'], $context['files'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 55
            echo "</table>";
        }
        // line 58
        echo "<h3>Configured metadata directories</h3>
<table>
    <thead>
    <tr>
        <th>Prefix</th>
        <th>Path</th>
    </tr>
    </thead>";
        // line 66
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 66, $this->source); })()), "loadedDirs", [], "any", false, false, false, 66));
        foreach ($context['_seq'] as $context["prefix"] => $context["path"]) {
            // line 67
            echo "<tr>
            <td>";
            // line 68
            echo twig_escape_filter($this->env, $context["prefix"], "html", null, true);
            echo "</td>
            <td>";
            // line 69
            echo twig_escape_filter($this->env, $context["path"], "html", null, true);
            echo "</td>
        </tr>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['prefix'], $context['path'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 72
        echo "</table>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@JMSSerializer/Collector/metadata.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 72,  168 => 69,  164 => 68,  161 => 67,  157 => 66,  148 => 58,  145 => 55,  138 => 52,  127 => 49,  122 => 48,  118 => 47,  114 => 45,  111 => 44,  107 => 43,  99 => 36,  94 => 32,  92 => 31,  90 => 30,  87 => 26,  80 => 23,  72 => 21,  68 => 20,  64 => 18,  61 => 17,  57 => 16,  49 => 9,  44 => 4,  42 => 3,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<h2>Loaded metadata</h2>

{%- if collector.loadedMetadata is empty -%}
    <div class=\"empty\">
        <p>No metadata have been loaded.</p>
    </div>
{%- else -%}

    <table>
        <thead>
        <tr>
            <th>Class</th>
            <th>File</th>
        </tr>
        </thead>
        {%- for class, files in collector.loadedMetadata -%}
            <tr>
                <td>{{ class }}</td>
                <td nowrap=\"nowrap\">
                    {%- for file in files -%}
                        {{ file }}<br/>
                    {% endfor %}
                </td>
            </tr>
        {%- endfor -%}
    </table>
{%- endif -%}


<h3>Attempted files</h3>
{%- if collector.metadataFiles is empty -%}
    <div class=\"empty\">
        <p>No metadata files attempted (if this list is empty, probably all the data are cached as expected)</p>
    </div>
{%- else -%}
    <table>
        <thead>
        <tr>
            <th>Class</th>
            <th>Files</th>
        </tr>
        </thead>
        {%- for class, files in collector.metadataFiles -%}
            <tr>
                <td>{{ class }}</td>
                <td nowrap=\"nowrap\">
                    {%- for file, found in files -%}
                        <span style=\"color: {{ found ? 'green': 'red' }}\">
                        {{ file }} ({{ found ? 'found': 'not found' }})<br/>
                        </span>
                    {% endfor %}
                </td>
            </tr>
        {%- endfor -%}
    </table>
{%- endif -%}

<h3>Configured metadata directories</h3>
<table>
    <thead>
    <tr>
        <th>Prefix</th>
        <th>Path</th>
    </tr>
    </thead>
    {%- for prefix, path in collector.loadedDirs -%}
        <tr>
            <td>{{ prefix }}</td>
            <td>{{ path }}</td>
        </tr>
    {%- endfor -%}
</table>
", "@JMSSerializer/Collector/metadata.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/jms/serializer-bundle/Resources/views/Collector/metadata.html.twig");
    }
}
