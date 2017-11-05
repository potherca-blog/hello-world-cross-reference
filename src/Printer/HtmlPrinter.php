<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

use Potherca\CrossReference\HelloWorld\Model\Language;

class HtmlPrinter extends AbstractPrinter
{
    final public function output()
    {
        $output = '';

        $languages = $this->getLanguages();

        $providers = [];
        array_walk($languages, function (Language $language) use (&$providers) {
            $providers = array_unique(array_merge($providers, $language->getProviders()));
        });

        sort($providers);

        $rows= '';
        array_walk($languages, function ($language) use (&$rows, $providers) {
            $cells = '';

            foreach($providers as $provider) {
                $present = in_array($provider, $language->getProviders());

                $cells .= vsprintf('<td class="%s">%s</td>', [
                    $present?'got-language':'not-language',
                    $present?'✔':'',
                ]);
            }

            $nameList = $language->getNames();

            $names = [];
            array_walk($nameList, function ($name, $provider) use (&$names) {
                $names[$name][] = $provider;
            });

            array_walk($names, function (&$providers, $name) {
                $providers = vsprintf('<a title="%s">%s</a>', [implode("\n", $providers), $name]);
            });

            $name = implode(' / ', $names);

            $rows .= vsprintf('<tr><th>%s</th>%s</tr>%s', [$name, $cells, PHP_EOL]);
        });



        $table = <<<'HTML'
                <table class="column table is-bordered is-striped is-narrow is-hoverable">
                    <thead>
                        <tr>
                            <th>
                                <div>
                                    <span>Language</span>
<!--
                                    <div class="field has-addons" style="
                                        padding: 0.25em 0em 0em 0;
                                    ">
                                        <div class="control has-icons-left">
                                            <input class="input" type="text" placeholder="Programming language">

                                            <span class="icon is-small is-left">
                                                <i class="fa fa-filter"></i>
                                            </span>
                                        </div>
                                        <div class="control">
                                            <a class="button is-info">Filter</a>
                                        </div>
                                    </div>
-->
                                </div>
                            </th>
                            <td><div><span>%s</span></div></td>
                        </tr>
                    </thead>
                    <tbody>%s</tbody>
                </table>
HTML;

        $table = vsprintf($table, [
            implode('</span></div></td><td><div><span>', $providers),
            $rows
        ]);


$html = <<<HTML
<html>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://pother.ca/CssBase/css/created-by-potherca.css" integrity="sha384-ym76993Fm3XsoIrV759tLiuGRkmdFDc5PmfKo/3h5/xpoAt5S6NbvItSoWTvikLJ" crossorigin="anonymous">

    <style>
        section {
            padding: 3rem;
        }

        .table {
            background: none;
            margin: 0 4em;
        }

        tbody tr {
            background: white;
        }

        tbody td {
            text-align: center;
        }

        .table thead th {
            font-size: 2rem;
            margin:0 !important;
            padding:0 !important;
            text-align: center;
            vertical-align: bottom;
        }

        thead th > div {
            background: white;
            padding: 0.5rem;
        }

        thead td {
            background-image: repeating-linear-gradient(
                180deg,
                transparent 0,
                transparent 4.8rem,
                white 0,
                white 100%
            );
            border-top: none;
            height: 1em;
        }

        thead td > div {
            transform: translate(0rem, 1rem) rotate(-40deg);
            width: 1.5rem;
        }

        thead td > div > span {
            background: white;
            border-bottom: 1px solid lightgray;
            border-top: 1px solid lightgray;
            display: block;
            line-height: 1.65rem;
            white-space: nowrap;
            width: 20rem;
        }

        thead td:first-of-type::before {
            background-image: repeating-linear-gradient(
                180deg,
                #209CEE 0,
                #209CEE 2rem,
                white 0,
                white 100%
            );
            content: " ";
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 300%;
        }

        thead th, thead td {
            position: sticky;
            top: 0;
        }

        h2.title {
            clear: both;
            line-height: 0;
            margin: 2rem;
            white-space: nowrap;
        }

        .created-by:hover .potherca::after,
        .created-by:hover .Potherca::after  {
            -webkit-transform: rotatex(0deg) translate(0, -8px);
            transform: rotatex(0deg) translate(0, -8px);
        }
        .created-by {
            display: block;
            font-weight: bold;
            line-height: 2rem;
            width: 100%;
        }
    </style>

    <section class="hero is-info is-medium">
        <div class="hero-head">
            <h1 class="title">
                Hello World!
            </h1>
            <h2 class="subtitle">
                A cross-reference of "hello world" collections.
            </h2>
        </div>

        <div class="hero-body">
            <div class="columns container has-text-centered">
                <div class="column">
                    <h2 class="title is-pulled-left has-text-info">What?</h2>
                    <p class="has-text-justified box">
                        This page contains a list of all the languages provided
                        by separate "hello world" repositories. It offers an
                        overview (or cross-reference) of which languages are
                        available in which repository.
                    </p>

                    <h2 class="title is-pulled-left has-text-info">Why?</h2>
                    <p class="has-text-justified box">
                        I came accross these "hello world" collections and I was
                        curious what the score was.
                    </p>

                    <h2 class="title is-pulled-left has-text-info">Who?</h2>
                    <p class="has-text-justified box">
                        <a href="https://pother.ca/" class="created-by"><span class="potherca">Potherca</span></a>
                    </p>
<!--
                    <h2 class="title is-pulled-left has-text-info">How?</h2>
                    <p class="has-text-justified box">
                        Visit the source code for details.
                    </p>
-->
                </div>
                {$table}
            </div>
        </div>

        <div class="hero-foot">
        </div>

    </section>
</html>
HTML;

        $output .= $html;

        return $output;
    }
}
