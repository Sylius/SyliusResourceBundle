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

/* crud/index.html.twig */
class __TwigTemplate_2bfa7c4967e77143a5b63e076352626d extends Template
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "crud/index.html.twig"));

        // line 1
        echo "<h1>";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["operation"]) || array_key_exists("operation", $context) ? $context["operation"] : (function () { throw new RuntimeError('Variable "operation" does not exist.', 1, $this->source); })()), "resource", [], "any", false, false, false, 1), "applicationName", [], "any", false, false, false, 1) . ".ui.") . twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["operation"]) || array_key_exists("operation", $context) ? $context["operation"] : (function () { throw new RuntimeError('Variable "operation" does not exist.', 1, $this->source); })()), "resource", [], "any", false, false, false, 1), "pluralName", [], "any", false, false, false, 1))), "html", null, true);
        echo "</h1>

";
        // line 3
        $context["grid"] = (isset($context["resources"]) || array_key_exists("resources", $context) ? $context["resources"] : (function () { throw new RuntimeError('Variable "resources" does not exist.', 3, $this->source); })());
        // line 4
        $context["definition"] = twig_get_attribute($this->env, $this->source, (isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 4, $this->source); })()), "definition", [], "any", false, false, false, 4);
        // line 5
        echo "
";
        // line 6
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["definition"] ?? null), "actionGroups", [], "any", false, true, false, 6), "bulk", [], "any", true, true, false, 6) && (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 6, $this->source); })()), "getEnabledActions", [0 => "bulk"], "method", false, false, false, 6)) > 0))) {
            // line 7
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 7, $this->source); })()), "getEnabledActions", [0 => "bulk"], "method", false, false, false, 7));
            foreach ($context['_seq'] as $context["_key"] => $context["action"]) {
                // line 8
                echo "        ";
                echo $this->env->getFunction('sylius_grid_render_bulk_action')->getCallable()((isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 8, $this->source); })()), $context["action"], null);
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['action'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 11
        echo "
<table>
    <thead>
        <tr>
            ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 15, $this->source); })()), "fields", [], "any", false, false, false, 15));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 16
            echo "                ";
            if (twig_get_attribute($this->env, $this->source, $context["field"], "enabled", [], "any", false, false, false, 16)) {
                // line 17
                echo "                    <th class=\"sylius-table-column-";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", [], "any", false, false, false, 17), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(twig_get_attribute($this->env, $this->source, $context["field"], "label", [], "any", false, false, false, 17)), "html", null, true);
                echo "</th>
                ";
            }
            // line 19
            echo "                ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["definition"] ?? null), "actionGroups", [], "any", false, true, false, 19), "item", [], "any", true, true, false, 19) && (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 19, $this->source); })()), "getEnabledActions", [0 => "item"], "method", false, false, false, 19)) > 0))) {
                // line 20
                echo "                    <th class=\"sylius-table-column-actions\">Actions</th>
                ";
            }
            // line 22
            echo "            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "        </tr>
    </thead>
    <tbody>
    ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["resources"]) || array_key_exists("resources", $context) ? $context["resources"] : (function () { throw new RuntimeError('Variable "resources" does not exist.', 26, $this->source); })()), "data", [], "any", false, false, false, 26));
        foreach ($context['_seq'] as $context["_key"] => $context["resource"]) {
            // line 27
            echo "        <tr>
            ";
            // line 28
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 28, $this->source); })()), "enabledFields", [], "any", false, false, false, 28));
            foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
                // line 29
                echo "                <td>";
                echo $this->env->getFunction('sylius_grid_render_field')->getCallable()((isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 29, $this->source); })()), $context["field"], $context["resource"]);
                echo "</td>
                <td>
                ";
                // line 31
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["definition"]) || array_key_exists("definition", $context) ? $context["definition"] : (function () { throw new RuntimeError('Variable "definition" does not exist.', 31, $this->source); })()), "getEnabledActions", [0 => "item"], "method", false, false, false, 31));
                foreach ($context['_seq'] as $context["_key"] => $context["action"]) {
                    // line 32
                    echo "                    ";
                    echo $this->env->getFunction('sylius_grid_render_action')->getCallable()((isset($context["grid"]) || array_key_exists("grid", $context) ? $context["grid"] : (function () { throw new RuntimeError('Variable "grid" does not exist.', 32, $this->source); })()), $context["action"], $context["resource"]);
                    echo "
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['action'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 34
                echo "                </td>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 36
            echo "        </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['resource'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        echo "    </tbody>

</table>

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "crud/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 38,  146 => 36,  139 => 34,  130 => 32,  126 => 31,  120 => 29,  116 => 28,  113 => 27,  109 => 26,  104 => 23,  98 => 22,  94 => 20,  91 => 19,  83 => 17,  80 => 16,  76 => 15,  70 => 11,  60 => 8,  55 => 7,  53 => 6,  50 => 5,  48 => 4,  46 => 3,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<h1>{{ (operation.resource.applicationName ~ '.ui.' ~ operation.resource.pluralName)|trans }}</h1>

{% set grid = resources %}
{% set definition = grid.definition %}

{% if definition.actionGroups.bulk is defined and definition.getEnabledActions('bulk')|length > 0 %}
    {% for action in definition.getEnabledActions('bulk') %}
        {{ sylius_grid_render_bulk_action(grid, action, null) }}
    {% endfor %}
{% endif %}

<table>
    <thead>
        <tr>
            {% for field in definition.fields %}
                {% if field.enabled %}
                    <th class=\"sylius-table-column-{{ field.name }}\">{{ field.label|trans }}</th>
                {% endif %}
                {% if definition.actionGroups.item is defined and definition.getEnabledActions('item')|length > 0 %}
                    <th class=\"sylius-table-column-actions\">Actions</th>
                {% endif %}
            {% endfor %}
        </tr>
    </thead>
    <tbody>
    {% for resource in resources.data %}
        <tr>
            {% for field in definition.enabledFields %}
                <td>{{ sylius_grid_render_field(grid, field, resource) }}</td>
                <td>
                {% for action in definition.getEnabledActions('item') %}
                    {{ sylius_grid_render_action(grid, action, resource) }}
                {% endfor %}
                </td>
            {% endfor %}
        </tr>
    {% endfor %}
    </tbody>

</table>

", "crud/index.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/tests/Application/templates/crud/index.html.twig");
    }
}
