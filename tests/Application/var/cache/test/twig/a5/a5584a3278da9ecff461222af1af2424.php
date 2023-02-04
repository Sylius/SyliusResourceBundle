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

/* bootstrap_5_layout.html.twig */
class __TwigTemplate_4ca352ec4165c0d06cab61b6ce79f2a8 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        // line 1
        $_trait_0 = $this->loadTemplate("bootstrap_base_layout.html.twig", "bootstrap_5_layout.html.twig", 1);
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."bootstrap_base_layout.html.twig".'" cannot be used as a trait.', 1, $this->source);
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            [
                'money_widget' => [$this, 'block_money_widget'],
                'date_widget' => [$this, 'block_date_widget'],
                'time_widget' => [$this, 'block_time_widget'],
                'datetime_widget' => [$this, 'block_datetime_widget'],
                'dateinterval_widget' => [$this, 'block_dateinterval_widget'],
                'percent_widget' => [$this, 'block_percent_widget'],
                'form_widget_simple' => [$this, 'block_form_widget_simple'],
                'widget_attributes' => [$this, 'block_widget_attributes'],
                'button_widget' => [$this, 'block_button_widget'],
                'submit_widget' => [$this, 'block_submit_widget'],
                'checkbox_widget' => [$this, 'block_checkbox_widget'],
                'radio_widget' => [$this, 'block_radio_widget'],
                'choice_widget_collapsed' => [$this, 'block_choice_widget_collapsed'],
                'choice_widget_expanded' => [$this, 'block_choice_widget_expanded'],
                'form_label' => [$this, 'block_form_label'],
                'checkbox_radio_label' => [$this, 'block_checkbox_radio_label'],
                'form_row' => [$this, 'block_form_row'],
                'button_row' => [$this, 'block_button_row'],
                'form_errors' => [$this, 'block_form_errors'],
                'form_help' => [$this, 'block_form_help'],
            ]
        );
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "bootstrap_5_layout.html.twig"));

        // line 2
        echo "
";
        // line 4
        echo "
";
        // line 5
        $this->displayBlock('money_widget', $context, $blocks);
        // line 22
        echo "
";
        // line 23
        $this->displayBlock('date_widget', $context, $blocks);
        // line 53
        echo "
";
        // line 54
        $this->displayBlock('time_widget', $context, $blocks);
        // line 92
        echo "
";
        // line 93
        $this->displayBlock('datetime_widget', $context, $blocks);
        // line 109
        echo "
";
        // line 110
        $this->displayBlock('dateinterval_widget', $context, $blocks);
        // line 165
        echo "
";
        // line 166
        $this->displayBlock('percent_widget', $context, $blocks);
        // line 176
        echo "
";
        // line 177
        $this->displayBlock('form_widget_simple', $context, $blocks);
        // line 194
        $this->displayBlock('widget_attributes', $context, $blocks);
        // line 201
        $this->displayBlock('button_widget', $context, $blocks);
        // line 206
        $this->displayBlock('submit_widget', $context, $blocks);
        // line 211
        $this->displayBlock('checkbox_widget', $context, $blocks);
        // line 235
        $this->displayBlock('radio_widget', $context, $blocks);
        // line 256
        $this->displayBlock('choice_widget_collapsed', $context, $blocks);
        // line 261
        $this->displayBlock('choice_widget_expanded', $context, $blocks);
        // line 272
        echo "
";
        // line 275
        $this->displayBlock('form_label', $context, $blocks);
        // line 294
        $this->displayBlock('checkbox_radio_label', $context, $blocks);
        // line 320
        echo "
";
        // line 323
        $this->displayBlock('form_row', $context, $blocks);
        // line 345
        $this->displayBlock('button_row', $context, $blocks);
        // line 350
        echo "
";
        // line 353
        $this->displayBlock('form_errors', $context, $blocks);
        // line 360
        echo "
";
        // line 363
        $this->displayBlock('form_help', $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
    public function block_money_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "money_widget"));

        // line 6
        $context["prepend"] =  !(is_string($__internal_compile_0 = (isset($context["money_pattern"]) || array_key_exists("money_pattern", $context) ? $context["money_pattern"] : (function () { throw new RuntimeError('Variable "money_pattern" does not exist.', 6, $this->source); })())) && is_string($__internal_compile_1 = "{{") && ('' === $__internal_compile_1 || 0 === strpos($__internal_compile_0, $__internal_compile_1)));
        // line 7
        $context["append"] =  !(is_string($__internal_compile_2 = (isset($context["money_pattern"]) || array_key_exists("money_pattern", $context) ? $context["money_pattern"] : (function () { throw new RuntimeError('Variable "money_pattern" does not exist.', 7, $this->source); })())) && is_string($__internal_compile_3 = "}}") && ('' === $__internal_compile_3 || $__internal_compile_3 === substr($__internal_compile_2, -strlen($__internal_compile_3))));
        // line 8
        if (((isset($context["prepend"]) || array_key_exists("prepend", $context) ? $context["prepend"] : (function () { throw new RuntimeError('Variable "prepend" does not exist.', 8, $this->source); })()) || (isset($context["append"]) || array_key_exists("append", $context) ? $context["append"] : (function () { throw new RuntimeError('Variable "append" does not exist.', 8, $this->source); })()))) {
            // line 9
            echo "<div class=\"input-group ";
            echo twig_escape_filter($this->env, ((array_key_exists("group_class", $context)) ? (_twig_default_filter((isset($context["group_class"]) || array_key_exists("group_class", $context) ? $context["group_class"] : (function () { throw new RuntimeError('Variable "group_class" does not exist.', 9, $this->source); })()), "")) : ("")), "html", null, true);
            echo "\">";
            // line 10
            if ((isset($context["prepend"]) || array_key_exists("prepend", $context) ? $context["prepend"] : (function () { throw new RuntimeError('Variable "prepend" does not exist.', 10, $this->source); })())) {
                // line 11
                echo "<span class=\"input-group-text\">";
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->encodeCurrency($this->env, (isset($context["money_pattern"]) || array_key_exists("money_pattern", $context) ? $context["money_pattern"] : (function () { throw new RuntimeError('Variable "money_pattern" does not exist.', 11, $this->source); })()));
                echo "</span>";
            }
            // line 13
            $this->displayBlock("form_widget_simple", $context, $blocks);
            // line 14
            if ((isset($context["append"]) || array_key_exists("append", $context) ? $context["append"] : (function () { throw new RuntimeError('Variable "append" does not exist.', 14, $this->source); })())) {
                // line 15
                echo "<span class=\"input-group-text\">";
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->encodeCurrency($this->env, (isset($context["money_pattern"]) || array_key_exists("money_pattern", $context) ? $context["money_pattern"] : (function () { throw new RuntimeError('Variable "money_pattern" does not exist.', 15, $this->source); })()));
                echo "</span>";
            }
            // line 17
            echo "</div>";
        } else {
            // line 19
            $this->displayBlock("form_widget_simple", $context, $blocks);
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 23
    public function block_date_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "date_widget"));

        // line 24
        if (((isset($context["widget"]) || array_key_exists("widget", $context) ? $context["widget"] : (function () { throw new RuntimeError('Variable "widget" does not exist.', 24, $this->source); })()) == "single_text")) {
            // line 25
            $this->displayBlock("form_widget_simple", $context, $blocks);
        } else {
            // line 27
            if ( !(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 27, $this->source); })())) {
                // line 28
                echo "            ";
                $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 28, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 28)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 28), "")) : ("")) . " is-invalid"))]);
                // line 29
                $context["valid"] = true;
                // line 30
                echo "        ";
            }
            // line 31
            if (( !array_key_exists("datetime", $context) ||  !(isset($context["datetime"]) || array_key_exists("datetime", $context) ? $context["datetime"] : (function () { throw new RuntimeError('Variable "datetime" does not exist.', 31, $this->source); })()))) {
                // line 32
                echo "<div ";
                $this->displayBlock("widget_container_attributes", $context, $blocks);
                echo ">";
            }
            // line 34
            if ( !((isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 34, $this->source); })()) === false)) {
                // line 35
                echo "<div class=\"visually-hidden\">";
                // line 36
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 36, $this->source); })()), "year", [], "any", false, false, false, 36), 'label');
                // line 37
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 37, $this->source); })()), "month", [], "any", false, false, false, 37), 'label');
                // line 38
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 38, $this->source); })()), "day", [], "any", false, false, false, 38), 'label');
                // line 39
                echo "</div>";
            }
            // line 41
            echo "<div class=\"input-group\">";
            // line 42
            echo twig_replace_filter((isset($context["date_pattern"]) || array_key_exists("date_pattern", $context) ? $context["date_pattern"] : (function () { throw new RuntimeError('Variable "date_pattern" does not exist.', 42, $this->source); })()), ["{{ year }}" =>             // line 43
$this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 43, $this->source); })()), "year", [], "any", false, false, false, 43), 'widget'), "{{ month }}" =>             // line 44
$this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 44, $this->source); })()), "month", [], "any", false, false, false, 44), 'widget'), "{{ day }}" =>             // line 45
$this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 45, $this->source); })()), "day", [], "any", false, false, false, 45), 'widget')]);
            // line 47
            echo "</div>";
            // line 48
            if (( !array_key_exists("datetime", $context) ||  !(isset($context["datetime"]) || array_key_exists("datetime", $context) ? $context["datetime"] : (function () { throw new RuntimeError('Variable "datetime" does not exist.', 48, $this->source); })()))) {
                // line 49
                echo "</div>";
            }
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 54
    public function block_time_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "time_widget"));

        // line 55
        if (((isset($context["widget"]) || array_key_exists("widget", $context) ? $context["widget"] : (function () { throw new RuntimeError('Variable "widget" does not exist.', 55, $this->source); })()) == "single_text")) {
            // line 56
            $this->displayBlock("form_widget_simple", $context, $blocks);
        } else {
            // line 58
            if ( !(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 58, $this->source); })())) {
                // line 59
                echo "            ";
                $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 59, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 59)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 59), "")) : ("")) . " is-invalid"))]);
                // line 60
                $context["valid"] = true;
                // line 61
                echo "        ";
            }
            // line 62
            if (( !array_key_exists("datetime", $context) || (false == (isset($context["datetime"]) || array_key_exists("datetime", $context) ? $context["datetime"] : (function () { throw new RuntimeError('Variable "datetime" does not exist.', 62, $this->source); })())))) {
                // line 63
                echo "<div ";
                $this->displayBlock("widget_container_attributes", $context, $blocks);
                echo ">";
            }
            // line 65
            if ( !((isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 65, $this->source); })()) === false)) {
                // line 66
                echo "<div class=\"visually-hidden\">";
                // line 67
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 67, $this->source); })()), "hour", [], "any", false, false, false, 67), 'label');
                // line 68
                if ((isset($context["with_minutes"]) || array_key_exists("with_minutes", $context) ? $context["with_minutes"] : (function () { throw new RuntimeError('Variable "with_minutes" does not exist.', 68, $this->source); })())) {
                    echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 68, $this->source); })()), "minute", [], "any", false, false, false, 68), 'label');
                }
                // line 69
                if ((isset($context["with_seconds"]) || array_key_exists("with_seconds", $context) ? $context["with_seconds"] : (function () { throw new RuntimeError('Variable "with_seconds" does not exist.', 69, $this->source); })())) {
                    echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 69, $this->source); })()), "second", [], "any", false, false, false, 69), 'label');
                }
                // line 70
                echo "</div>";
            }
            // line 72
            if (((isset($context["with_minutes"]) || array_key_exists("with_minutes", $context) ? $context["with_minutes"] : (function () { throw new RuntimeError('Variable "with_minutes" does not exist.', 72, $this->source); })()) || (isset($context["with_seconds"]) || array_key_exists("with_seconds", $context) ? $context["with_seconds"] : (function () { throw new RuntimeError('Variable "with_seconds" does not exist.', 72, $this->source); })()))) {
                // line 73
                echo "            <div class=\"input-group\">
        ";
            }
            // line 75
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 75, $this->source); })()), "hour", [], "any", false, false, false, 75), 'widget');
            // line 76
            if ((isset($context["with_minutes"]) || array_key_exists("with_minutes", $context) ? $context["with_minutes"] : (function () { throw new RuntimeError('Variable "with_minutes" does not exist.', 76, $this->source); })())) {
                // line 77
                echo "<span class=\"input-group-text\">:</span>";
                // line 78
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 78, $this->source); })()), "minute", [], "any", false, false, false, 78), 'widget');
            }
            // line 80
            if ((isset($context["with_seconds"]) || array_key_exists("with_seconds", $context) ? $context["with_seconds"] : (function () { throw new RuntimeError('Variable "with_seconds" does not exist.', 80, $this->source); })())) {
                // line 81
                echo "<span class=\"input-group-text\">:</span>";
                // line 82
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 82, $this->source); })()), "second", [], "any", false, false, false, 82), 'widget');
            }
            // line 84
            if (((isset($context["with_minutes"]) || array_key_exists("with_minutes", $context) ? $context["with_minutes"] : (function () { throw new RuntimeError('Variable "with_minutes" does not exist.', 84, $this->source); })()) || (isset($context["with_seconds"]) || array_key_exists("with_seconds", $context) ? $context["with_seconds"] : (function () { throw new RuntimeError('Variable "with_seconds" does not exist.', 84, $this->source); })()))) {
                // line 85
                echo "            </div>
        ";
            }
            // line 87
            if (( !array_key_exists("datetime", $context) || (false == (isset($context["datetime"]) || array_key_exists("datetime", $context) ? $context["datetime"] : (function () { throw new RuntimeError('Variable "datetime" does not exist.', 87, $this->source); })())))) {
                // line 88
                echo "</div>";
            }
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 93
    public function block_datetime_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "datetime_widget"));

        // line 94
        if (((isset($context["widget"]) || array_key_exists("widget", $context) ? $context["widget"] : (function () { throw new RuntimeError('Variable "widget" does not exist.', 94, $this->source); })()) == "single_text")) {
            // line 95
            $this->displayBlock("form_widget_simple", $context, $blocks);
        } else {
            // line 97
            if ( !(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 97, $this->source); })())) {
                // line 98
                echo "            ";
                $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 98, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 98)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 98), "")) : ("")) . " is-invalid"))]);
                // line 99
                $context["valid"] = true;
                // line 100
                echo "        ";
            }
            // line 101
            echo "        <div ";
            $this->displayBlock("widget_container_attributes", $context, $blocks);
            echo ">";
            // line 102
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 102, $this->source); })()), "date", [], "any", false, false, false, 102), 'widget', ["datetime" => true]);
            // line 103
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 103, $this->source); })()), "date", [], "any", false, false, false, 103), 'errors');
            // line 104
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 104, $this->source); })()), "time", [], "any", false, false, false, 104), 'widget', ["datetime" => true]);
            // line 105
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 105, $this->source); })()), "time", [], "any", false, false, false, 105), 'errors');
            // line 106
            echo "</div>";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 110
    public function block_dateinterval_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "dateinterval_widget"));

        // line 111
        if (((isset($context["widget"]) || array_key_exists("widget", $context) ? $context["widget"] : (function () { throw new RuntimeError('Variable "widget" does not exist.', 111, $this->source); })()) == "single_text")) {
            // line 112
            $this->displayBlock("form_widget_simple", $context, $blocks);
        } else {
            // line 114
            if ( !(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 114, $this->source); })())) {
                // line 115
                echo "            ";
                $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 115, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 115)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 115), "")) : ("")) . " is-invalid"))]);
                // line 116
                $context["valid"] = true;
                // line 117
                echo "        ";
            }
            // line 118
            echo "        <div ";
            $this->displayBlock("widget_container_attributes", $context, $blocks);
            echo ">";
            // line 119
            if ((isset($context["with_years"]) || array_key_exists("with_years", $context) ? $context["with_years"] : (function () { throw new RuntimeError('Variable "with_years" does not exist.', 119, $this->source); })())) {
                // line 120
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 121
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 121, $this->source); })()), "years", [], "any", false, false, false, 121), 'label');
                echo "
                    ";
                // line 122
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 122, $this->source); })()), "years", [], "any", false, false, false, 122), 'widget');
                echo "
                </div>";
            }
            // line 125
            if ((isset($context["with_months"]) || array_key_exists("with_months", $context) ? $context["with_months"] : (function () { throw new RuntimeError('Variable "with_months" does not exist.', 125, $this->source); })())) {
                // line 126
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 127
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 127, $this->source); })()), "months", [], "any", false, false, false, 127), 'label');
                echo "
                    ";
                // line 128
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 128, $this->source); })()), "months", [], "any", false, false, false, 128), 'widget');
                echo "
                </div>";
            }
            // line 131
            if ((isset($context["with_weeks"]) || array_key_exists("with_weeks", $context) ? $context["with_weeks"] : (function () { throw new RuntimeError('Variable "with_weeks" does not exist.', 131, $this->source); })())) {
                // line 132
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 133
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 133, $this->source); })()), "weeks", [], "any", false, false, false, 133), 'label');
                echo "
                    ";
                // line 134
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 134, $this->source); })()), "weeks", [], "any", false, false, false, 134), 'widget');
                echo "
                </div>";
            }
            // line 137
            if ((isset($context["with_days"]) || array_key_exists("with_days", $context) ? $context["with_days"] : (function () { throw new RuntimeError('Variable "with_days" does not exist.', 137, $this->source); })())) {
                // line 138
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 139
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 139, $this->source); })()), "days", [], "any", false, false, false, 139), 'label');
                echo "
                    ";
                // line 140
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 140, $this->source); })()), "days", [], "any", false, false, false, 140), 'widget');
                echo "
                </div>";
            }
            // line 143
            if ((isset($context["with_hours"]) || array_key_exists("with_hours", $context) ? $context["with_hours"] : (function () { throw new RuntimeError('Variable "with_hours" does not exist.', 143, $this->source); })())) {
                // line 144
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 145
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 145, $this->source); })()), "hours", [], "any", false, false, false, 145), 'label');
                echo "
                    ";
                // line 146
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 146, $this->source); })()), "hours", [], "any", false, false, false, 146), 'widget');
                echo "
                </div>";
            }
            // line 149
            if ((isset($context["with_minutes"]) || array_key_exists("with_minutes", $context) ? $context["with_minutes"] : (function () { throw new RuntimeError('Variable "with_minutes" does not exist.', 149, $this->source); })())) {
                // line 150
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 151
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 151, $this->source); })()), "minutes", [], "any", false, false, false, 151), 'label');
                echo "
                    ";
                // line 152
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 152, $this->source); })()), "minutes", [], "any", false, false, false, 152), 'widget');
                echo "
                </div>";
            }
            // line 155
            if ((isset($context["with_seconds"]) || array_key_exists("with_seconds", $context) ? $context["with_seconds"] : (function () { throw new RuntimeError('Variable "with_seconds" does not exist.', 155, $this->source); })())) {
                // line 156
                echo "<div class=\"col-auto mb-3\">
                    ";
                // line 157
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 157, $this->source); })()), "seconds", [], "any", false, false, false, 157), 'label');
                echo "
                    ";
                // line 158
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 158, $this->source); })()), "seconds", [], "any", false, false, false, 158), 'widget');
                echo "
                </div>";
            }
            // line 161
            if ((isset($context["with_invert"]) || array_key_exists("with_invert", $context) ? $context["with_invert"] : (function () { throw new RuntimeError('Variable "with_invert" does not exist.', 161, $this->source); })())) {
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 161, $this->source); })()), "invert", [], "any", false, false, false, 161), 'widget');
            }
            // line 162
            echo "</div>";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 166
    public function block_percent_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "percent_widget"));

        // line 167
        if ((isset($context["symbol"]) || array_key_exists("symbol", $context) ? $context["symbol"] : (function () { throw new RuntimeError('Variable "symbol" does not exist.', 167, $this->source); })())) {
            // line 168
            echo "<div class=\"input-group\">";
            // line 169
            $this->displayBlock("form_widget_simple", $context, $blocks);
            // line 170
            echo "<span class=\"input-group-text\">";
            echo twig_escape_filter($this->env, ((array_key_exists("symbol", $context)) ? (_twig_default_filter((isset($context["symbol"]) || array_key_exists("symbol", $context) ? $context["symbol"] : (function () { throw new RuntimeError('Variable "symbol" does not exist.', 170, $this->source); })()), "%")) : ("%")), "html", null, true);
            echo "</span>
        </div>";
        } else {
            // line 173
            $this->displayBlock("form_widget_simple", $context, $blocks);
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 177
    public function block_form_widget_simple($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "form_widget_simple"));

        // line 178
        if (( !array_key_exists("type", $context) || ((isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 178, $this->source); })()) != "hidden"))) {
            // line 179
            $context["widget_class"] = " form-control";
            // line 180
            if ((((array_key_exists("type", $context)) ? (_twig_default_filter((isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 180, $this->source); })()), "")) : ("")) == "color")) {
                // line 181
                $context["widget_class"] = ((isset($context["widget_class"]) || array_key_exists("widget_class", $context) ? $context["widget_class"] : (function () { throw new RuntimeError('Variable "widget_class" does not exist.', 181, $this->source); })()) . " form-control-color");
            } elseif ((((            // line 182
array_key_exists("type", $context)) ? (_twig_default_filter((isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 182, $this->source); })()), "")) : ("")) == "range")) {
                // line 183
                $context["widget_class"] = " form-range";
            }
            // line 185
            $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 185, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 185)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 185), "")) : ("")) . (isset($context["widget_class"]) || array_key_exists("widget_class", $context) ? $context["widget_class"] : (function () { throw new RuntimeError('Variable "widget_class" does not exist.', 185, $this->source); })())))]);
        }
        // line 187
        if ((array_key_exists("type", $context) && twig_in_filter((isset($context["type"]) || array_key_exists("type", $context) ? $context["type"] : (function () { throw new RuntimeError('Variable "type" does not exist.', 187, $this->source); })()), [0 => "range", 1 => "color"]))) {
            // line 188
            echo "        ";
            // line 189
            echo "        ";
            $context["required"] = false;
            // line 190
            echo "    ";
        }
        // line 191
        $this->displayParentBlock("form_widget_simple", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 194
    public function block_widget_attributes($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "widget_attributes"));

        // line 195
        if ( !(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 195, $this->source); })())) {
            // line 196
            echo "        ";
            $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 196, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 196)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 196), "")) : ("")) . " is-invalid"))]);
            // line 197
            echo "    ";
        }
        // line 198
        $this->displayParentBlock("widget_attributes", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 201
    public function block_button_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "button_widget"));

        // line 202
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 202, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 202)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 202), "btn-secondary")) : ("btn-secondary")) . " btn"))]);
        // line 203
        $this->displayParentBlock("button_widget", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 206
    public function block_submit_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "submit_widget"));

        // line 207
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 207, $this->source); })()), ["class" => twig_trim_filter(((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 207)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 207), "btn-primary")) : ("btn-primary")))]);
        // line 208
        $this->displayParentBlock("submit_widget", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 211
    public function block_checkbox_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "checkbox_widget"));

        // line 212
        $context["attr_class"] = ((array_key_exists("attr_class", $context)) ? (_twig_default_filter((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 212, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 212)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 212), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 212)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 212), "")) : (""))));
        // line 213
        $context["row_class"] = "";
        // line 214
        if (!twig_in_filter("btn-check", (isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 214, $this->source); })()))) {
            // line 215
            $context["attr_class"] = ((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 215, $this->source); })()) . " form-check-input");
            // line 216
            $context["row_class"] = "form-check";
        }
        // line 218
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 218, $this->source); })()), ["class" => twig_trim_filter((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 218, $this->source); })()))]);
        // line 219
        $context["parent_label_class"] = ((array_key_exists("parent_label_class", $context)) ? (_twig_default_filter((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 219, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 219)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 219), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 219)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 219), "")) : (""))));
        // line 220
        if (twig_in_filter("checkbox-inline", (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 220, $this->source); })()))) {
            // line 221
            $context["row_class"] = ((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 221, $this->source); })()) . " form-check-inline");
        }
        // line 223
        if (twig_in_filter("checkbox-switch", (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 223, $this->source); })()))) {
            // line 224
            $context["row_class"] = ((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 224, $this->source); })()) . " form-switch");
        }
        // line 226
        if ( !twig_test_empty((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 226, $this->source); })()))) {
            // line 227
            echo "<div class=\"";
            echo twig_escape_filter($this->env, (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 227, $this->source); })()), "html", null, true);
            echo "\">";
        }
        // line 229
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 229, $this->source); })()), 'label', ["widget" => $this->renderParentBlock("checkbox_widget", $context, $blocks)]);
        // line 230
        if ( !twig_test_empty((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 230, $this->source); })()))) {
            // line 231
            echo "</div>";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 235
    public function block_radio_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "radio_widget"));

        // line 236
        $context["attr_class"] = ((array_key_exists("attr_class", $context)) ? (_twig_default_filter((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 236, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 236)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 236), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 236)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 236), "")) : (""))));
        // line 237
        $context["row_class"] = "";
        // line 238
        if (!twig_in_filter("btn-check", (isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 238, $this->source); })()))) {
            // line 239
            $context["attr_class"] = ((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 239, $this->source); })()) . " form-check-input");
            // line 240
            $context["row_class"] = "form-check";
        }
        // line 242
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 242, $this->source); })()), ["class" => twig_trim_filter((isset($context["attr_class"]) || array_key_exists("attr_class", $context) ? $context["attr_class"] : (function () { throw new RuntimeError('Variable "attr_class" does not exist.', 242, $this->source); })()))]);
        // line 243
        $context["parent_label_class"] = ((array_key_exists("parent_label_class", $context)) ? (_twig_default_filter((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 243, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 243)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 243), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 243)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 243), "")) : (""))));
        // line 244
        if (twig_in_filter("radio-inline", (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 244, $this->source); })()))) {
            // line 245
            $context["row_class"] = ((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 245, $this->source); })()) . " form-check-inline");
        }
        // line 247
        if ( !twig_test_empty((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 247, $this->source); })()))) {
            // line 248
            echo "<div class=\"";
            echo twig_escape_filter($this->env, (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 248, $this->source); })()), "html", null, true);
            echo "\">";
        }
        // line 250
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 250, $this->source); })()), 'label', ["widget" => $this->renderParentBlock("radio_widget", $context, $blocks)]);
        // line 251
        if ( !twig_test_empty((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 251, $this->source); })()))) {
            // line 252
            echo "</div>";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 256
    public function block_choice_widget_collapsed($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "choice_widget_collapsed"));

        // line 257
        $context["attr"] = twig_array_merge((isset($context["attr"]) || array_key_exists("attr", $context) ? $context["attr"] : (function () { throw new RuntimeError('Variable "attr" does not exist.', 257, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 257)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 257), "")) : ("")) . " form-select"))]);
        // line 258
        $this->displayParentBlock("choice_widget_collapsed", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 261
    public function block_choice_widget_expanded($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "choice_widget_expanded"));

        // line 262
        echo "<div ";
        $this->displayBlock("widget_container_attributes", $context, $blocks);
        echo ">";
        // line 263
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 263, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 264
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($context["child"], 'widget', ["parent_label_class" => ((twig_get_attribute($this->env, $this->source,             // line 265
($context["label_attr"] ?? null), "class", [], "any", true, true, false, 265)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 265), "")) : ("")), "translation_domain" =>             // line 266
(isset($context["choice_translation_domain"]) || array_key_exists("choice_translation_domain", $context) ? $context["choice_translation_domain"] : (function () { throw new RuntimeError('Variable "choice_translation_domain" does not exist.', 266, $this->source); })()), "valid" =>             // line 267
(isset($context["valid"]) || array_key_exists("valid", $context) ? $context["valid"] : (function () { throw new RuntimeError('Variable "valid" does not exist.', 267, $this->source); })())]);
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 270
        echo "</div>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 275
    public function block_form_label($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "form_label"));

        // line 276
        if ( !((isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 276, $this->source); })()) === false)) {
            // line 277
            $context["parent_label_class"] = ((array_key_exists("parent_label_class", $context)) ? (_twig_default_filter((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 277, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 277)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 277), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 277)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 277), "")) : (""))));
            // line 278
            if ((array_key_exists("compound", $context) && (isset($context["compound"]) || array_key_exists("compound", $context) ? $context["compound"] : (function () { throw new RuntimeError('Variable "compound" does not exist.', 278, $this->source); })()))) {
                // line 279
                $context["element"] = "legend";
                // line 280
                if (!twig_in_filter("col-form-label", (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 280, $this->source); })()))) {
                    // line 281
                    $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 281, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 281)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 281), "")) : ("")) . " col-form-label"))]);
                }
            } else {
                // line 284
                $context["row_class"] = ((array_key_exists("row_class", $context)) ? (_twig_default_filter((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 284, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 284)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 284), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 284)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 284), "")) : (""))));
                // line 285
                $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 285, $this->source); })()), ["for" => (isset($context["id"]) || array_key_exists("id", $context) ? $context["id"] : (function () { throw new RuntimeError('Variable "id" does not exist.', 285, $this->source); })())]);
                // line 286
                if (!twig_in_filter("col-form-label", (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 286, $this->source); })()))) {
                    // line 287
                    $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 287, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 287)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 287), "")) : ("")) . ((twig_in_filter("input-group", (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 287, $this->source); })()))) ? (" input-group-text") : (" form-label"))))]);
                }
            }
        }
        // line 291
        $this->displayParentBlock("form_label", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 294
    public function block_checkbox_radio_label($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "checkbox_radio_label"));

        // line 296
        if (array_key_exists("widget", $context)) {
            // line 297
            $context["label_attr_class"] = ((array_key_exists("label_attr_class", $context)) ? (_twig_default_filter((isset($context["label_attr_class"]) || array_key_exists("label_attr_class", $context) ? $context["label_attr_class"] : (function () { throw new RuntimeError('Variable "label_attr_class" does not exist.', 297, $this->source); })()), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 297)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 297), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 297)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 297), "")) : (""))));
            // line 298
            if (!twig_in_filter("btn", (isset($context["label_attr_class"]) || array_key_exists("label_attr_class", $context) ? $context["label_attr_class"] : (function () { throw new RuntimeError('Variable "label_attr_class" does not exist.', 298, $this->source); })()))) {
                // line 299
                $context["label_attr_class"] = ((isset($context["label_attr_class"]) || array_key_exists("label_attr_class", $context) ? $context["label_attr_class"] : (function () { throw new RuntimeError('Variable "label_attr_class" does not exist.', 299, $this->source); })()) . " form-check-label");
            }
            // line 301
            $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 301, $this->source); })()), ["class" => twig_trim_filter((isset($context["label_attr_class"]) || array_key_exists("label_attr_class", $context) ? $context["label_attr_class"] : (function () { throw new RuntimeError('Variable "label_attr_class" does not exist.', 301, $this->source); })()))]);
            // line 302
            if ( !(isset($context["compound"]) || array_key_exists("compound", $context) ? $context["compound"] : (function () { throw new RuntimeError('Variable "compound" does not exist.', 302, $this->source); })())) {
                // line 303
                $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 303, $this->source); })()), ["for" => (isset($context["id"]) || array_key_exists("id", $context) ? $context["id"] : (function () { throw new RuntimeError('Variable "id" does not exist.', 303, $this->source); })())]);
            }
            // line 305
            if ((isset($context["required"]) || array_key_exists("required", $context) ? $context["required"] : (function () { throw new RuntimeError('Variable "required" does not exist.', 305, $this->source); })())) {
                // line 306
                $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 306, $this->source); })()), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 306)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 306), "")) : ("")) . " required"))]);
            }
            // line 308
            if (array_key_exists("parent_label_class", $context)) {
                // line 309
                $context["label_attr"] = twig_array_merge((isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 309, $this->source); })()), ["class" => twig_trim_filter(twig_replace_filter(((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 309)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 309), "")) : ("")) . " ") . (isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context) ? $context["parent_label_class"] : (function () { throw new RuntimeError('Variable "parent_label_class" does not exist.', 309, $this->source); })())), ["checkbox-inline" => "", "radio-inline" => ""]))]);
            }
            // line 312
            echo (isset($context["widget"]) || array_key_exists("widget", $context) ? $context["widget"] : (function () { throw new RuntimeError('Variable "widget" does not exist.', 312, $this->source); })());
            echo "
        <label";
            // line 313
            $__internal_compile_4 = $context;
            $__internal_compile_5 = ["attr" => (isset($context["label_attr"]) || array_key_exists("label_attr", $context) ? $context["label_attr"] : (function () { throw new RuntimeError('Variable "label_attr" does not exist.', 313, $this->source); })())];
            if (!twig_test_iterable($__internal_compile_5)) {
                throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 313, $this->getSourceContext());
            }
            $__internal_compile_5 = twig_to_array($__internal_compile_5);
            $context = $this->env->mergeGlobals(array_merge($context, $__internal_compile_5));
            $this->displayBlock("attributes", $context, $blocks);
            $context = $__internal_compile_4;
            echo ">";
            // line 314
            if ( !((isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 314, $this->source); })()) === false)) {
                // line 315
                $this->displayBlock("form_label_content", $context, $blocks);
            }
            // line 317
            echo "</label>";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 323
    public function block_form_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "form_row"));

        // line 324
        if ((array_key_exists("compound", $context) && (isset($context["compound"]) || array_key_exists("compound", $context) ? $context["compound"] : (function () { throw new RuntimeError('Variable "compound" does not exist.', 324, $this->source); })()))) {
            // line 325
            $context["element"] = "fieldset";
        }
        // line 327
        $context["widget_attr"] = [];
        // line 328
        if ( !twig_test_empty((isset($context["help"]) || array_key_exists("help", $context) ? $context["help"] : (function () { throw new RuntimeError('Variable "help" does not exist.', 328, $this->source); })()))) {
            // line 329
            $context["widget_attr"] = ["attr" => ["aria-describedby" => ((isset($context["id"]) || array_key_exists("id", $context) ? $context["id"] : (function () { throw new RuntimeError('Variable "id" does not exist.', 329, $this->source); })()) . "_help")]];
        }
        // line 331
        $context["row_class"] = ((array_key_exists("row_class", $context)) ? (_twig_default_filter((isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 331, $this->source); })()), twig_trim_filter(((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 331)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 331), "mb-3")) : ("mb-3"))))) : (twig_trim_filter(((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 331)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 331), "mb-3")) : ("mb-3")))));
        // line 332
        echo "<";
        echo twig_escape_filter($this->env, ((array_key_exists("element", $context)) ? (_twig_default_filter((isset($context["element"]) || array_key_exists("element", $context) ? $context["element"] : (function () { throw new RuntimeError('Variable "element" does not exist.', 332, $this->source); })()), "div")) : ("div")), "html", null, true);
        $__internal_compile_6 = $context;
        $__internal_compile_7 = ["attr" => twig_array_merge((isset($context["row_attr"]) || array_key_exists("row_attr", $context) ? $context["row_attr"] : (function () { throw new RuntimeError('Variable "row_attr" does not exist.', 332, $this->source); })()), ["class" => (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 332, $this->source); })())])];
        if (!twig_test_iterable($__internal_compile_7)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 332, $this->getSourceContext());
        }
        $__internal_compile_7 = twig_to_array($__internal_compile_7);
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_compile_7));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $__internal_compile_6;
        echo ">";
        // line 333
        if (twig_in_filter("form-floating", (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 333, $this->source); })()))) {
            // line 334
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 334, $this->source); })()), 'widget', (isset($context["widget_attr"]) || array_key_exists("widget_attr", $context) ? $context["widget_attr"] : (function () { throw new RuntimeError('Variable "widget_attr" does not exist.', 334, $this->source); })()));
            // line 335
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 335, $this->source); })()), 'label');
        } else {
            // line 337
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 337, $this->source); })()), 'label');
            // line 338
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 338, $this->source); })()), 'widget', (isset($context["widget_attr"]) || array_key_exists("widget_attr", $context) ? $context["widget_attr"] : (function () { throw new RuntimeError('Variable "widget_attr" does not exist.', 338, $this->source); })()));
        }
        // line 340
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 340, $this->source); })()), 'help');
        // line 341
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 341, $this->source); })()), 'errors');
        // line 342
        echo "</";
        echo twig_escape_filter($this->env, ((array_key_exists("element", $context)) ? (_twig_default_filter((isset($context["element"]) || array_key_exists("element", $context) ? $context["element"] : (function () { throw new RuntimeError('Variable "element" does not exist.', 342, $this->source); })()), "div")) : ("div")), "html", null, true);
        echo ">";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 345
    public function block_button_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "button_row"));

        // line 346
        echo "<div";
        $__internal_compile_8 = $context;
        $__internal_compile_9 = ["attr" => twig_array_merge((isset($context["row_attr"]) || array_key_exists("row_attr", $context) ? $context["row_attr"] : (function () { throw new RuntimeError('Variable "row_attr" does not exist.', 346, $this->source); })()), ["class" => twig_trim_filter(((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 346)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 346), "mb-3")) : ("mb-3")))])];
        if (!twig_test_iterable($__internal_compile_9)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 346, $this->getSourceContext());
        }
        $__internal_compile_9 = twig_to_array($__internal_compile_9);
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_compile_9));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $__internal_compile_8;
        echo ">";
        // line 347
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 347, $this->source); })()), 'widget');
        // line 348
        echo "</div>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 353
    public function block_form_errors($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "form_errors"));

        // line 354
        if ((twig_length_filter($this->env, (isset($context["errors"]) || array_key_exists("errors", $context) ? $context["errors"] : (function () { throw new RuntimeError('Variable "errors" does not exist.', 354, $this->source); })())) > 0)) {
            // line 355
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["errors"]) || array_key_exists("errors", $context) ? $context["errors"] : (function () { throw new RuntimeError('Variable "errors" does not exist.', 355, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                // line 356
                echo "<div class=\"";
                if ( !Symfony\Bridge\Twig\Extension\twig_is_root_form((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 356, $this->source); })()))) {
                    echo "invalid-feedback";
                } else {
                    echo "alert alert-danger";
                }
                echo " d-block\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["error"], "message", [], "any", false, false, false, 356), "html", null, true);
                echo "</div>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 363
    public function block_form_help($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "form_help"));

        // line 364
        $context["row_class"] = ((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 364)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 364), "")) : (""));
        // line 365
        echo "    ";
        $context["help_class"] = " form-text";
        // line 366
        echo "    ";
        if (twig_in_filter("input-group", (isset($context["row_class"]) || array_key_exists("row_class", $context) ? $context["row_class"] : (function () { throw new RuntimeError('Variable "row_class" does not exist.', 366, $this->source); })()))) {
            // line 368
            $context["help_class"] = " input-group-text";
            // line 369
            echo "    ";
        }
        // line 370
        if ( !twig_test_empty((isset($context["help"]) || array_key_exists("help", $context) ? $context["help"] : (function () { throw new RuntimeError('Variable "help" does not exist.', 370, $this->source); })()))) {
            // line 371
            $context["help_attr"] = twig_array_merge((isset($context["help_attr"]) || array_key_exists("help_attr", $context) ? $context["help_attr"] : (function () { throw new RuntimeError('Variable "help_attr" does not exist.', 371, $this->source); })()), ["class" => twig_trim_filter(((((twig_get_attribute($this->env, $this->source, ($context["help_attr"] ?? null), "class", [], "any", true, true, false, 371)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["help_attr"] ?? null), "class", [], "any", false, false, false, 371), "")) : ("")) . (isset($context["help_class"]) || array_key_exists("help_class", $context) ? $context["help_class"] : (function () { throw new RuntimeError('Variable "help_class" does not exist.', 371, $this->source); })())) . " mb-0"))]);
        }
        // line 373
        $this->displayParentBlock("form_help", $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "bootstrap_5_layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  1032 => 373,  1029 => 371,  1027 => 370,  1024 => 369,  1022 => 368,  1019 => 366,  1016 => 365,  1014 => 364,  1007 => 363,  987 => 356,  983 => 355,  981 => 354,  974 => 353,  967 => 348,  965 => 347,  953 => 346,  946 => 345,  937 => 342,  935 => 341,  933 => 340,  930 => 338,  928 => 337,  925 => 335,  923 => 334,  921 => 333,  908 => 332,  906 => 331,  903 => 329,  901 => 328,  899 => 327,  896 => 325,  894 => 324,  887 => 323,  879 => 317,  876 => 315,  874 => 314,  863 => 313,  859 => 312,  856 => 309,  854 => 308,  851 => 306,  849 => 305,  846 => 303,  844 => 302,  842 => 301,  839 => 299,  837 => 298,  835 => 297,  833 => 296,  826 => 294,  819 => 291,  814 => 287,  812 => 286,  810 => 285,  808 => 284,  804 => 281,  802 => 280,  800 => 279,  798 => 278,  796 => 277,  794 => 276,  787 => 275,  780 => 270,  774 => 267,  773 => 266,  772 => 265,  771 => 264,  767 => 263,  763 => 262,  756 => 261,  749 => 258,  747 => 257,  740 => 256,  732 => 252,  730 => 251,  728 => 250,  723 => 248,  721 => 247,  718 => 245,  716 => 244,  714 => 243,  712 => 242,  709 => 240,  707 => 239,  705 => 238,  703 => 237,  701 => 236,  694 => 235,  686 => 231,  684 => 230,  682 => 229,  677 => 227,  675 => 226,  672 => 224,  670 => 223,  667 => 221,  665 => 220,  663 => 219,  661 => 218,  658 => 216,  656 => 215,  654 => 214,  652 => 213,  650 => 212,  643 => 211,  636 => 208,  634 => 207,  627 => 206,  620 => 203,  618 => 202,  611 => 201,  604 => 198,  601 => 197,  598 => 196,  596 => 195,  589 => 194,  582 => 191,  579 => 190,  576 => 189,  574 => 188,  572 => 187,  569 => 185,  566 => 183,  564 => 182,  562 => 181,  560 => 180,  558 => 179,  556 => 178,  549 => 177,  541 => 173,  535 => 170,  533 => 169,  531 => 168,  529 => 167,  522 => 166,  514 => 162,  510 => 161,  505 => 158,  501 => 157,  498 => 156,  496 => 155,  491 => 152,  487 => 151,  484 => 150,  482 => 149,  477 => 146,  473 => 145,  470 => 144,  468 => 143,  463 => 140,  459 => 139,  456 => 138,  454 => 137,  449 => 134,  445 => 133,  442 => 132,  440 => 131,  435 => 128,  431 => 127,  428 => 126,  426 => 125,  421 => 122,  417 => 121,  414 => 120,  412 => 119,  408 => 118,  405 => 117,  403 => 116,  400 => 115,  398 => 114,  395 => 112,  393 => 111,  386 => 110,  378 => 106,  376 => 105,  374 => 104,  372 => 103,  370 => 102,  366 => 101,  363 => 100,  361 => 99,  358 => 98,  356 => 97,  353 => 95,  351 => 94,  344 => 93,  335 => 88,  333 => 87,  329 => 85,  327 => 84,  324 => 82,  322 => 81,  320 => 80,  317 => 78,  315 => 77,  313 => 76,  311 => 75,  307 => 73,  305 => 72,  302 => 70,  298 => 69,  294 => 68,  292 => 67,  290 => 66,  288 => 65,  283 => 63,  281 => 62,  278 => 61,  276 => 60,  273 => 59,  271 => 58,  268 => 56,  266 => 55,  259 => 54,  250 => 49,  248 => 48,  246 => 47,  244 => 45,  243 => 44,  242 => 43,  241 => 42,  239 => 41,  236 => 39,  234 => 38,  232 => 37,  230 => 36,  228 => 35,  226 => 34,  221 => 32,  219 => 31,  216 => 30,  214 => 29,  211 => 28,  209 => 27,  206 => 25,  204 => 24,  197 => 23,  189 => 19,  186 => 17,  181 => 15,  179 => 14,  177 => 13,  172 => 11,  170 => 10,  166 => 9,  164 => 8,  162 => 7,  160 => 6,  153 => 5,  146 => 363,  143 => 360,  141 => 353,  138 => 350,  136 => 345,  134 => 323,  131 => 320,  129 => 294,  127 => 275,  124 => 272,  122 => 261,  120 => 256,  118 => 235,  116 => 211,  114 => 206,  112 => 201,  110 => 194,  108 => 177,  105 => 176,  103 => 166,  100 => 165,  98 => 110,  95 => 109,  93 => 93,  90 => 92,  88 => 54,  85 => 53,  83 => 23,  80 => 22,  78 => 5,  75 => 4,  72 => 2,  30 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% use \"bootstrap_base_layout.html.twig\" %}

{# Widgets #}

{% block money_widget -%}
    {%- set prepend = not (money_pattern starts with '{{') -%}
    {%- set append = not (money_pattern ends with '}}') -%}
    {%- if prepend or append -%}
        <div class=\"input-group {{ group_class|default('') }}\">
            {%- if prepend -%}
                <span class=\"input-group-text\">{{ money_pattern|form_encode_currency }}</span>
            {%- endif -%}
            {{- block('form_widget_simple') -}}
            {%- if append -%}
                <span class=\"input-group-text\">{{ money_pattern|form_encode_currency }}</span>
            {%- endif -%}
        </div>
    {%- else -%}
        {{- block('form_widget_simple') -}}
    {%- endif -%}
{%- endblock money_widget %}

{% block date_widget -%}
    {%- if widget == 'single_text' -%}
        {{- block('form_widget_simple') -}}
    {%- else -%}
        {% if not valid %}
            {% set attr = attr|merge({class: (attr.class|default('') ~ ' is-invalid')|trim}) -%}
            {% set valid = true %}
        {% endif %}
        {%- if datetime is not defined or not datetime -%}
            <div {{ block('widget_container_attributes') -}}>
        {%- endif %}
        {%- if label is not same as(false) -%}
            <div class=\"visually-hidden\">
                {{- form_label(form.year) -}}
                {{- form_label(form.month) -}}
                {{- form_label(form.day) -}}
            </div>
        {%- endif -%}
        <div class=\"input-group\">
            {{- date_pattern|replace({
                '{{ year }}': form_widget(form.year),
                '{{ month }}': form_widget(form.month),
                '{{ day }}': form_widget(form.day),
            })|raw -}}
        </div>
        {%- if datetime is not defined or not datetime -%}
            </div>
        {%- endif -%}
    {%- endif -%}
{%- endblock date_widget %}

{% block time_widget -%}
    {%- if widget == 'single_text' -%}
        {{- block('form_widget_simple') -}}
    {%- else -%}
        {% if not valid %}
            {% set attr = attr|merge({class: (attr.class|default('') ~ ' is-invalid')|trim}) -%}
            {% set valid = true %}
        {% endif %}
        {%- if datetime is not defined or false == datetime -%}
            <div {{ block('widget_container_attributes') -}}>
        {%- endif -%}
        {%- if label is not same as(false) -%}
            <div class=\"visually-hidden\">
                {{- form_label(form.hour) -}}
                {%- if with_minutes -%}{{ form_label(form.minute) }}{%- endif -%}
                {%- if with_seconds -%}{{ form_label(form.second) }}{%- endif -%}
            </div>
        {%- endif -%}
        {% if with_minutes or with_seconds %}
            <div class=\"input-group\">
        {% endif %}
        {{- form_widget(form.hour) -}}
        {%- if with_minutes -%}
            <span class=\"input-group-text\">:</span>
            {{- form_widget(form.minute) -}}
        {%- endif -%}
        {%- if with_seconds -%}
            <span class=\"input-group-text\">:</span>
            {{- form_widget(form.second) -}}
        {%- endif -%}
        {% if with_minutes or with_seconds %}
            </div>
        {% endif %}
        {%- if datetime is not defined or false == datetime -%}
            </div>
        {%- endif -%}
    {%- endif -%}
{%- endblock time_widget %}

{% block datetime_widget -%}
    {%- if widget == 'single_text' -%}
        {{- block('form_widget_simple') -}}
    {%- else -%}
        {% if not valid %}
            {% set attr = attr|merge({class: (attr.class|default('') ~ ' is-invalid')|trim}) -%}
            {% set valid = true %}
        {% endif %}
        <div {{ block('widget_container_attributes') }}>
            {{- form_widget(form.date, { datetime: true } ) -}}
            {{- form_errors(form.date) -}}
            {{- form_widget(form.time, { datetime: true } ) -}}
            {{- form_errors(form.time) -}}
        </div>
    {%- endif -%}
{%- endblock datetime_widget %}

{% block dateinterval_widget -%}
    {%- if widget == 'single_text' -%}
        {{- block('form_widget_simple') -}}
    {%- else -%}
        {% if not valid %}
            {% set attr = attr|merge({class: (attr.class|default('') ~ ' is-invalid')|trim}) -%}
            {% set valid = true %}
        {% endif %}
        <div {{ block('widget_container_attributes') }}>
            {%- if with_years -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.years) }}
                    {{ form_widget(form.years) }}
                </div>
            {%- endif -%}
            {%- if with_months -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.months) }}
                    {{ form_widget(form.months) }}
                </div>
            {%- endif -%}
            {%- if with_weeks -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.weeks) }}
                    {{ form_widget(form.weeks) }}
                </div>
            {%- endif -%}
            {%- if with_days -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.days) }}
                    {{ form_widget(form.days) }}
                </div>
            {%- endif -%}
            {%- if with_hours -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.hours) }}
                    {{ form_widget(form.hours) }}
                </div>
            {%- endif -%}
            {%- if with_minutes -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.minutes) }}
                    {{ form_widget(form.minutes) }}
                </div>
            {%- endif -%}
            {%- if with_seconds -%}
                <div class=\"col-auto mb-3\">
                    {{ form_label(form.seconds) }}
                    {{ form_widget(form.seconds) }}
                </div>
            {%- endif -%}
            {%- if with_invert %}{{ form_widget(form.invert) }}{% endif -%}
        </div>
    {%- endif -%}
{%- endblock dateinterval_widget %}

{% block percent_widget -%}
    {%- if symbol -%}
        <div class=\"input-group\">
            {{- block('form_widget_simple') -}}
            <span class=\"input-group-text\">{{ symbol|default('%') }}</span>
        </div>
    {%- else -%}
        {{- block('form_widget_simple') -}}
    {%- endif -%}
{%- endblock percent_widget %}

{% block form_widget_simple -%}
    {%- if type is not defined or type != 'hidden' %}
        {%- set widget_class = ' form-control' %}
        {%- if type|default('') == 'color' -%}
            {%- set widget_class = widget_class ~ ' form-control-color' -%}
        {%- elseif type|default('') == 'range' -%}
            {%- set widget_class = ' form-range' -%}
        {%- endif -%}
        {%- set attr = attr|merge({class: (attr.class|default('') ~ widget_class)|trim}) -%}
    {% endif -%}
    {%- if type is defined and type in ['range', 'color'] %}
        {# Attribute \"required\" is not supported #}
        {% set required = false %}
    {% endif -%}
    {{- parent() -}}
{%- endblock form_widget_simple %}

{%- block widget_attributes -%}
    {%- if not valid %}
        {% set attr = attr|merge({class: (attr.class|default('') ~ ' is-invalid')|trim}) %}
    {% endif -%}
    {{ parent() }}
{%- endblock widget_attributes -%}

{%- block button_widget -%}
    {%- set attr = attr|merge({class: (attr.class|default('btn-secondary') ~ ' btn')|trim}) -%}
    {{- parent() -}}
{%- endblock button_widget %}

{%- block submit_widget -%}
    {%- set attr = attr|merge({class: (attr.class|default('btn-primary'))|trim}) -%}
    {{- parent() -}}
{%- endblock submit_widget %}

{%- block checkbox_widget -%}
    {%- set attr_class = attr_class|default(attr.class|default('')) -%}
    {%- set row_class = '' -%}
    {%- if 'btn-check' not in attr_class -%}
        {%- set attr_class = attr_class ~ ' form-check-input' -%}
        {%- set row_class = 'form-check' -%}
    {%- endif -%}
    {%- set attr = attr|merge({class: attr_class|trim}) -%}
    {%- set parent_label_class = parent_label_class|default(label_attr.class|default('')) -%}
    {%- if 'checkbox-inline' in parent_label_class %}
        {%- set row_class = row_class ~ ' form-check-inline' -%}
    {% endif -%}
    {%- if 'checkbox-switch' in parent_label_class %}
        {%- set row_class = row_class ~ ' form-switch' -%}
    {% endif -%}
    {%- if row_class is not empty -%}
        <div class=\"{{ row_class }}\">
    {%- endif -%}
    {{- form_label(form, null, { widget: parent() }) -}}
    {%- if row_class is not empty -%}
        </div>
    {%- endif -%}
{%- endblock checkbox_widget %}

{%- block radio_widget -%}
    {%- set attr_class = attr_class|default(attr.class|default('')) -%}
    {%- set row_class = '' -%}
    {%- if 'btn-check' not in attr_class -%}
        {%- set attr_class = attr_class ~ ' form-check-input' -%}
        {%- set row_class = 'form-check' -%}
    {%- endif -%}
    {%- set attr = attr|merge({class: attr_class|trim}) -%}
    {%- set parent_label_class = parent_label_class|default(label_attr.class|default('')) -%}
    {%- if 'radio-inline' in parent_label_class -%}
        {%- set row_class = row_class ~ ' form-check-inline' -%}
    {%- endif -%}
    {%- if row_class is not empty -%}
        <div class=\"{{ row_class }}\">
    {%- endif -%}
    {{- form_label(form, null, { widget: parent() }) -}}
    {%- if row_class is not empty -%}
        </div>
    {%- endif -%}
{%- endblock radio_widget %}

{%- block choice_widget_collapsed -%}
    {%- set attr = attr|merge({class: (attr.class|default('') ~ ' form-select')|trim}) -%}
    {{- parent() -}}
{%- endblock choice_widget_collapsed -%}

{%- block choice_widget_expanded -%}
    <div {{ block('widget_container_attributes') }}>
        {%- for child in form %}
            {{- form_widget(child, {
                parent_label_class: label_attr.class|default(''),
                translation_domain: choice_translation_domain,
                valid: valid,
            }) -}}
        {% endfor -%}
    </div>
{%- endblock choice_widget_expanded %}

{# Labels #}

{%- block form_label -%}
    {% if label is not same as(false) -%}
        {%- set parent_label_class = parent_label_class|default(label_attr.class|default('')) -%}
        {%- if compound is defined and compound -%}
            {%- set element = 'legend' -%}
            {%- if 'col-form-label' not in parent_label_class -%}
                {%- set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' col-form-label' )|trim}) -%}
            {%- endif -%}
        {%- else -%}
            {%- set row_class = row_class|default(row_attr.class|default('')) -%}
            {%- set label_attr = label_attr|merge({for: id}) -%}
            {%- if 'col-form-label' not in parent_label_class -%}
                {%- set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ('input-group' in row_class ? ' input-group-text' : ' form-label') )|trim}) -%}
            {%- endif -%}
        {%- endif -%}
    {%- endif -%}
    {{- parent() -}}
{%- endblock form_label %}

{%- block checkbox_radio_label -%}
    {#- Do not display the label if widget is not defined in order to prevent double label rendering -#}
    {%- if widget is defined -%}
        {%- set label_attr_class = label_attr_class|default(label_attr.class|default('')) -%}
        {%- if 'btn' not in label_attr_class -%}
            {%- set label_attr_class = label_attr_class ~ ' form-check-label' -%}
        {%- endif -%}
        {%- set label_attr = label_attr|merge({class: label_attr_class|trim}) -%}
        {%- if not compound -%}
            {% set label_attr = label_attr|merge({'for': id}) %}
        {%- endif -%}
        {%- if required -%}
            {%- set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' required')|trim}) -%}
        {%- endif -%}
        {%- if parent_label_class is defined -%}
            {%- set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' ' ~ parent_label_class)|replace({'checkbox-inline': '', 'radio-inline': ''})|trim}) -%}
        {%- endif -%}

        {{ widget|raw }}
        <label{% with { attr: label_attr } %}{{ block('attributes') }}{% endwith %}>
            {%- if label is not same as(false) -%}
                {{- block('form_label_content') -}}
            {%- endif -%}
        </label>
    {%- endif -%}
{%- endblock checkbox_radio_label %}

{# Rows #}

{%- block form_row -%}
    {%- if compound is defined and compound -%}
        {%- set element = 'fieldset' -%}
    {%- endif -%}
    {%- set widget_attr = {} -%}
    {%- if help is not empty -%}
        {%- set widget_attr = {attr: {'aria-describedby': id ~\"_help\"}} -%}
    {%- endif -%}
    {%- set row_class = row_class|default(row_attr.class|default('mb-3')|trim) -%}
    <{{ element|default('div') }}{% with {attr: row_attr|merge({class: row_class})} %}{{ block('attributes') }}{% endwith %}>
        {%- if 'form-floating' in row_class -%}
            {{- form_widget(form, widget_attr) -}}
            {{- form_label(form) -}}
        {%- else -%}
            {{- form_label(form) -}}
            {{- form_widget(form, widget_attr) -}}
        {%- endif -%}
        {{- form_help(form) -}}
        {{- form_errors(form) -}}
    </{{ element|default('div') }}>
{%- endblock form_row %}

{%- block button_row -%}
    <div{% with {attr: row_attr|merge({class: row_attr.class|default('mb-3')|trim})} %}{{ block('attributes') }}{% endwith %}>
        {{- form_widget(form) -}}
    </div>
{%- endblock button_row %}

{# Errors #}

{%- block form_errors -%}
    {%- if errors|length > 0 -%}
        {%- for error in errors -%}
            <div class=\"{% if form is not rootform %}invalid-feedback{% else %}alert alert-danger{% endif %} d-block\">{{ error.message }}</div>
        {%- endfor -%}
    {%- endif %}
{%- endblock form_errors %}

{# Help #}

{%- block form_help -%}
    {% set row_class = row_attr.class|default('') %}
    {% set help_class = ' form-text' %}
    {% if 'input-group' in row_class %}
        {#- Hack to properly display help with input group -#}
        {% set help_class = ' input-group-text' %}
    {% endif %}
    {%- if help is not empty -%}
        {%- set help_attr = help_attr|merge({class: (help_attr.class|default('') ~ help_class ~ ' mb-0')|trim}) -%}
    {%- endif -%}
    {{- parent() -}}
{%- endblock form_help %}
", "bootstrap_5_layout.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/symfony/twig-bridge/Resources/views/Form/bootstrap_5_layout.html.twig");
    }
}
