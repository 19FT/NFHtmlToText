<?php
// Run on command line: php test.php

include __DIR__.'/HtmlToText.php';

$html = <<<'EOT'
<html>
    <head>
        <title>Some Title</title>
        <style>
            Some styles here
            </style>
    </head>
    <body>

    <img src="logo.png">

    <h1>Hello World</h1>

    <p>Lorem ipsum <b>dolor sit</b> amet, consectetur adipiscing elit. Praeteritis, inquit, gaudeo.


        Dici enim <br>nihil potest verius. Duo Reges: constructio interrete. Haec para/doca illi,

        nos admirabilia dicamus. Comprehensum, quod cognitum non habet? Quoniam, si dis placet?

    </p>

    <ul>
        <li>one</li>
        <li>two</li>
        <li>three</li>
    </ul>

    <ol>
        <li>one</li>
        <li>two</li>
        <li>three</li>
    </ol>
    <p>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Haec para/doca illi, nos admirabilia dicamus. Age, inquies, ista parva sunt. Suo genere perveniant ad extremum;
    </p>

    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2</td>
                <td>Item #1</td>
            </tr>
            <tr>
                <td>532</td>
                <td>Item #2</td>
            </tr>
            <tr>
                <td>0</td>
                <td>Item #3</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </tfoot>
    </table>
    </body>
</html>
EOT;

$htmlToText = new \NF\HtmlToText;
echo $htmlToText->convert($html);
exit;
