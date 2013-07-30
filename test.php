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
    </body>
</html>
EOT;

$htmlToText = new \NF\HtmlToText;
echo $htmlToText->convert($html);
exit;
