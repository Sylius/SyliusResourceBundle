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

/* @Pagerfanta/default.html.twig */
class __TwigTemplate_e3714e72c0b6ea51cbdde03dcc128bc6 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'pager_widget' => [$this, 'block_pager_widget'],
            'pager' => [$this, 'block_pager'],
            'page_link' => [$this, 'block_page_link'],
            'current_page_link' => [$this, 'block_current_page_link'],
            'previous_page_link' => [$this, 'block_previous_page_link'],
            'previous_page_link_disabled' => [$this, 'block_previous_page_link_disabled'],
            'previous_page_message' => [$this, 'block_previous_page_message'],
            'next_page_link' => [$this, 'block_next_page_link'],
            'next_page_link_disabled' => [$this, 'block_next_page_link_disabled'],
            'next_page_message' => [$this, 'block_next_page_message'],
            'ellipsis' => [$this, 'block_ellipsis'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@Pagerfanta/default.html.twig"));

        // line 1
        $this->displayBlock('pager_widget', $context, $blocks);
        // line 7
        $this->displayBlock('pager', $context, $blocks);
        // line 73
        $this->displayBlock('page_link', $context, $blocks);
        // line 77
        $this->displayBlock('current_page_link', $context, $blocks);
        // line 81
        $this->displayBlock('previous_page_link', $context, $blocks);
        // line 85
        $this->displayBlock('previous_page_link_disabled', $context, $blocks);
        // line 89
        $this->displayBlock('previous_page_message', $context, $blocks);
        // line 97
        $this->displayBlock('next_page_link', $context, $blocks);
        // line 101
        $this->displayBlock('next_page_link_disabled', $context, $blocks);
        // line 105
        $this->displayBlock('next_page_message', $context, $blocks);
        // line 113
        $this->displayBlock('ellipsis', $context, $blocks);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 1
    public function block_pager_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "pager_widget"));

        // line 2
        echo "<nav class=\"pagination\">";
        // line 3
        $this->displayBlock("pager", $context, $blocks);
        // line 4
        echo "</nav>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 7
    public function block_pager($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "pager"));

        // line 9
        if (twig_get_attribute($this->env, $this->source, (isset($context["pagerfanta"]) || array_key_exists("pagerfanta", $context) ? $context["pagerfanta"] : (function () { throw new RuntimeError('Variable "pagerfanta" does not exist.', 9, $this->source); })()), "hasPreviousPage", [], "method", false, false, false, 9)) {
            // line 10
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 10, $this->source); })()), "route", [0 => twig_get_attribute($this->env, $this->source, (isset($context["pagerfanta"]) || array_key_exists("pagerfanta", $context) ? $context["pagerfanta"] : (function () { throw new RuntimeError('Variable "pagerfanta" does not exist.', 10, $this->source); })()), "getPreviousPage", [], "method", false, false, false, 10)], "method", false, false, false, 10);
            // line 11
            $this->displayBlock("previous_page_link", $context, $blocks);
        } else {
            // line 13
            $this->displayBlock("previous_page_link_disabled", $context, $blocks);
        }
        // line 17
        if (((isset($context["start_page"]) || array_key_exists("start_page", $context) ? $context["start_page"] : (function () { throw new RuntimeError('Variable "start_page" does not exist.', 17, $this->source); })()) > 1)) {
            // line 18
            $context["page"] = 1;
            // line 19
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 19, $this->source); })()), "route", [0 => (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 19, $this->source); })())], "method", false, false, false, 19);
            // line 20
            $this->displayBlock("page_link", $context, $blocks);
        }
        // line 24
        if (((isset($context["start_page"]) || array_key_exists("start_page", $context) ? $context["start_page"] : (function () { throw new RuntimeError('Variable "start_page" does not exist.', 24, $this->source); })()) == 3)) {
            // line 25
            $context["page"] = 2;
            // line 26
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 26, $this->source); })()), "route", [0 => (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 26, $this->source); })())], "method", false, false, false, 26);
            // line 27
            $this->displayBlock("page_link", $context, $blocks);
        }
        // line 31
        if (((isset($context["start_page"]) || array_key_exists("start_page", $context) ? $context["start_page"] : (function () { throw new RuntimeError('Variable "start_page" does not exist.', 31, $this->source); })()) > 3)) {
            // line 32
            $this->displayBlock("ellipsis", $context, $blocks);
        }
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range((isset($context["start_page"]) || array_key_exists("start_page", $context) ? $context["start_page"] : (function () { throw new RuntimeError('Variable "start_page" does not exist.', 36, $this->source); })()), (isset($context["end_page"]) || array_key_exists("end_page", $context) ? $context["end_page"] : (function () { throw new RuntimeError('Variable "end_page" does not exist.', 36, $this->source); })())));
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
        foreach ($context['_seq'] as $context["_key"] => $context["page"]) {
            // line 37
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 37, $this->source); })()), "route", [0 => $context["page"]], "method", false, false, false, 37);
            // line 38
            if (($context["page"] == (isset($context["current_page"]) || array_key_exists("current_page", $context) ? $context["current_page"] : (function () { throw new RuntimeError('Variable "current_page" does not exist.', 38, $this->source); })()))) {
                // line 39
                $this->displayBlock("current_page_link", $context, $blocks);
            } else {
                // line 41
                $this->displayBlock("page_link", $context, $blocks);
            }
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['page'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        if (((isset($context["end_page"]) || array_key_exists("end_page", $context) ? $context["end_page"] : (function () { throw new RuntimeError('Variable "end_page" does not exist.', 46, $this->source); })()) < ((isset($context["nb_pages"]) || array_key_exists("nb_pages", $context) ? $context["nb_pages"] : (function () { throw new RuntimeError('Variable "nb_pages" does not exist.', 46, $this->source); })()) - 2))) {
            // line 47
            $this->displayBlock("ellipsis", $context, $blocks);
        }
        // line 51
        if (((isset($context["end_page"]) || array_key_exists("end_page", $context) ? $context["end_page"] : (function () { throw new RuntimeError('Variable "end_page" does not exist.', 51, $this->source); })()) == ((isset($context["nb_pages"]) || array_key_exists("nb_pages", $context) ? $context["nb_pages"] : (function () { throw new RuntimeError('Variable "nb_pages" does not exist.', 51, $this->source); })()) - 2))) {
            // line 52
            $context["page"] = ((isset($context["nb_pages"]) || array_key_exists("nb_pages", $context) ? $context["nb_pages"] : (function () { throw new RuntimeError('Variable "nb_pages" does not exist.', 52, $this->source); })()) - 1);
            // line 53
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 53, $this->source); })()), "route", [0 => (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 53, $this->source); })())], "method", false, false, false, 53);
            // line 54
            $this->displayBlock("page_link", $context, $blocks);
        }
        // line 58
        if (((isset($context["nb_pages"]) || array_key_exists("nb_pages", $context) ? $context["nb_pages"] : (function () { throw new RuntimeError('Variable "nb_pages" does not exist.', 58, $this->source); })()) > (isset($context["end_page"]) || array_key_exists("end_page", $context) ? $context["end_page"] : (function () { throw new RuntimeError('Variable "end_page" does not exist.', 58, $this->source); })()))) {
            // line 59
            $context["page"] = (isset($context["nb_pages"]) || array_key_exists("nb_pages", $context) ? $context["nb_pages"] : (function () { throw new RuntimeError('Variable "nb_pages" does not exist.', 59, $this->source); })());
            // line 60
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 60, $this->source); })()), "route", [0 => (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 60, $this->source); })())], "method", false, false, false, 60);
            // line 61
            $this->displayBlock("page_link", $context, $blocks);
        }
        // line 65
        if (twig_get_attribute($this->env, $this->source, (isset($context["pagerfanta"]) || array_key_exists("pagerfanta", $context) ? $context["pagerfanta"] : (function () { throw new RuntimeError('Variable "pagerfanta" does not exist.', 65, $this->source); })()), "hasNextPage", [], "method", false, false, false, 65)) {
            // line 66
            $context["path"] = twig_get_attribute($this->env, $this->source, (isset($context["route_generator"]) || array_key_exists("route_generator", $context) ? $context["route_generator"] : (function () { throw new RuntimeError('Variable "route_generator" does not exist.', 66, $this->source); })()), "route", [0 => twig_get_attribute($this->env, $this->source, (isset($context["pagerfanta"]) || array_key_exists("pagerfanta", $context) ? $context["pagerfanta"] : (function () { throw new RuntimeError('Variable "pagerfanta" does not exist.', 66, $this->source); })()), "getNextPage", [], "method", false, false, false, 66)], "method", false, false, false, 66);
            // line 67
            $this->displayBlock("next_page_link", $context, $blocks);
        } else {
            // line 69
            $this->displayBlock("next_page_link_disabled", $context, $blocks);
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 73
    public function block_page_link($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_link"));

        // line 74
        echo "<a class=\"pagination__item\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["path"]) || array_key_exists("path", $context) ? $context["path"] : (function () { throw new RuntimeError('Variable "path" does not exist.', 74, $this->source); })()), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 74, $this->source); })()), "html", null, true);
        echo "</a>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 77
    public function block_current_page_link($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "current_page_link"));

        // line 78
        echo "<span class=\"pagination__item pagination__item--current-page\" aria-current=\"page\">";
        echo twig_escape_filter($this->env, (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new RuntimeError('Variable "page" does not exist.', 78, $this->source); })()), "html", null, true);
        echo "</span>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 81
    public function block_previous_page_link($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "previous_page_link"));

        // line 82
        echo "<a class=\"pagination__item pagination__item--previous-page\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["path"]) || array_key_exists("path", $context) ? $context["path"] : (function () { throw new RuntimeError('Variable "path" does not exist.', 82, $this->source); })()), "html", null, true);
        echo "\" rel=\"prev\">";
        $this->displayBlock("previous_page_message", $context, $blocks);
        echo "</a>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 85
    public function block_previous_page_link_disabled($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "previous_page_link_disabled"));

        // line 86
        echo "<span class=\"pagination__item pagination__item--previous-page pagination__item--disabled\">";
        $this->displayBlock("previous_page_message", $context, $blocks);
        echo "</span>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 89
    public function block_previous_page_message($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "previous_page_message"));

        // line 90
        if (twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "prev_message", [], "array", true, true, false, 90)) {
            // line 91
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 91, $this->source); })()), "prev_message", [], "array", false, false, false, 91), "html", null, true);
        } else {
            // line 93
            echo "Previous";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 97
    public function block_next_page_link($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "next_page_link"));

        // line 98
        echo "<a class=\"pagination__item pagination__item--next-page\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["path"]) || array_key_exists("path", $context) ? $context["path"] : (function () { throw new RuntimeError('Variable "path" does not exist.', 98, $this->source); })()), "html", null, true);
        echo "\" rel=\"next\">";
        $this->displayBlock("next_page_message", $context, $blocks);
        echo "</a>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 101
    public function block_next_page_link_disabled($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "next_page_link_disabled"));

        // line 102
        echo "<span class=\"pagination__item pagination__item--next-page pagination__item--disabled\">";
        $this->displayBlock("next_page_message", $context, $blocks);
        echo "</span>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 105
    public function block_next_page_message($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "next_page_message"));

        // line 106
        if (twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "next_message", [], "array", true, true, false, 106)) {
            // line 107
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 107, $this->source); })()), "next_message", [], "array", false, false, false, 107), "html", null, true);
        } else {
            // line 109
            echo "Next";
        }
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 113
    public function block_ellipsis($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "ellipsis"));

        // line 114
        echo "<span class=\"pagination__item pagination__item--separator\">&hellip;</span>";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@Pagerfanta/default.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  362 => 114,  355 => 113,  347 => 109,  344 => 107,  342 => 106,  335 => 105,  326 => 102,  319 => 101,  308 => 98,  301 => 97,  293 => 93,  290 => 91,  288 => 90,  281 => 89,  272 => 86,  265 => 85,  254 => 82,  247 => 81,  238 => 78,  231 => 77,  220 => 74,  213 => 73,  205 => 69,  202 => 67,  200 => 66,  198 => 65,  195 => 61,  193 => 60,  191 => 59,  189 => 58,  186 => 54,  184 => 53,  182 => 52,  180 => 51,  177 => 47,  175 => 46,  160 => 41,  157 => 39,  155 => 38,  153 => 37,  136 => 36,  133 => 32,  131 => 31,  128 => 27,  126 => 26,  124 => 25,  122 => 24,  119 => 20,  117 => 19,  115 => 18,  113 => 17,  110 => 13,  107 => 11,  105 => 10,  103 => 9,  96 => 7,  89 => 4,  87 => 3,  85 => 2,  78 => 1,  71 => 113,  69 => 105,  67 => 101,  65 => 97,  63 => 89,  61 => 85,  59 => 81,  57 => 77,  55 => 73,  53 => 7,  51 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{%- block pager_widget -%}
    <nav class=\"pagination\">
        {{- block('pager') -}}
    </nav>
{%- endblock pager_widget -%}

{%- block pager -%}
    {# Previous Page Link #}
    {%- if pagerfanta.hasPreviousPage() -%}
        {%- set path = route_generator.route(pagerfanta.getPreviousPage()) -%}
        {{- block('previous_page_link') -}}
    {%- else -%}
        {{- block('previous_page_link_disabled') -}}
    {%- endif -%}

    {# First Page Link #}
    {%- if start_page > 1 -%}
        {%- set page = 1 -%}
        {%- set path = route_generator.route(page) -%}
        {{- block('page_link') -}}
    {%- endif -%}

    {# Second Page Link, displays if we are on page 3 #}
    {%- if start_page == 3 -%}
        {%- set page = 2 -%}
        {%- set path = route_generator.route(page) -%}
        {{- block('page_link') -}}
    {%- endif -%}

    {# Separator, creates a \"...\" separator to limit the number of items if we are starting beyond page 3 #}
    {%- if start_page > 3 -%}
        {{- block('ellipsis') -}}
    {%- endif -%}

    {# Page Links #}
    {%- for page in range(start_page, end_page) -%}
        {%- set path = route_generator.route(page) -%}
        {%- if page == current_page -%}
            {{- block('current_page_link') -}}
        {%- else -%}
            {{- block('page_link') -}}
        {%- endif -%}
    {%- endfor -%}

    {# Separator, creates a \"...\" separator to limit the number of items if we are over 3 pages away from the last page #}
    {%- if end_page < (nb_pages - 2) -%}
        {{- block('ellipsis') -}}
    {%- endif -%}

    {# Second to Last Page Link, displays if we are on the third from last page #}
    {%- if end_page == (nb_pages - 2) -%}
        {%- set page = (nb_pages - 1) -%}
        {%- set path = route_generator.route(page) -%}
        {{- block('page_link') -}}
    {%- endif -%}

    {# Last Page Link #}
    {%- if nb_pages > end_page -%}
        {%- set page = nb_pages -%}
        {%- set path = route_generator.route(page) -%}
        {{- block('page_link') -}}
    {%- endif -%}

    {# Next Page Link #}
    {%- if pagerfanta.hasNextPage() -%}
        {%- set path = route_generator.route(pagerfanta.getNextPage()) -%}
        {{- block('next_page_link') -}}
    {%- else -%}
        {{- block('next_page_link_disabled') -}}
    {%- endif -%}
{%- endblock pager -%}

{%- block page_link -%}
    <a class=\"pagination__item\" href=\"{{- path -}}\">{{- page -}}</a>
{%- endblock page_link -%}

{%- block current_page_link -%}
    <span class=\"pagination__item pagination__item--current-page\" aria-current=\"page\">{{- page -}}</span>
{%- endblock current_page_link -%}

{%- block previous_page_link -%}
    <a class=\"pagination__item pagination__item--previous-page\" href=\"{{- path -}}\" rel=\"prev\">{{- block('previous_page_message') -}}</a>
{%- endblock previous_page_link -%}

{%- block previous_page_link_disabled -%}
    <span class=\"pagination__item pagination__item--previous-page pagination__item--disabled\">{{- block('previous_page_message') -}}</span>
{%- endblock previous_page_link_disabled -%}

{%- block previous_page_message -%}
    {%- if options['prev_message'] is defined -%}
        {{- options['prev_message'] -}}
    {%- else -%}
        Previous
    {%- endif -%}
{%- endblock previous_page_message -%}

{%- block next_page_link -%}
    <a class=\"pagination__item pagination__item--next-page\" href=\"{{- path -}}\" rel=\"next\">{{- block('next_page_message') -}}</a>
{%- endblock next_page_link -%}

{%- block next_page_link_disabled -%}
    <span class=\"pagination__item pagination__item--next-page pagination__item--disabled\">{{- block('next_page_message') -}}</span>
{%- endblock next_page_link_disabled -%}

{%- block next_page_message -%}
    {%- if options['next_message'] is defined -%}
        {{- options['next_message'] -}}
    {%- else -%}
        Next
    {%- endif -%}
{%- endblock next_page_message -%}

{%- block ellipsis -%}
    <span class=\"pagination__item pagination__item--separator\">&hellip;</span>
{%- endblock ellipsis -%}
", "@Pagerfanta/default.html.twig", "/home/loic/www/sylius/SyliusResourceBundle/vendor/pagerfanta/pagerfanta/lib/Twig/templates/default.html.twig");
    }
}
