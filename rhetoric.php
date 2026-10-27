<?php
    # Program: rhetoric.php
    #    Date: 30-Apr-2023
    #  Author: gerry.walterscheid@gmail.com
    # Descrip: Display types of rhetoric and their meaning.

    session_start();
    header("Content-type: text/xml");

    $delay = 60;
    header("Refresh:$delay");

    $urlPath = "http://192.168.3.250/cisco/rhetoric.php";

    $rhetoric = array(
        "Logos",
        "Language crafted to appeal to logic and reasoning, supported with facts and data.",
        "None of the kids were home when the cookie jar was raided, so the cookie thief couldn't have been one of them.",

        "Ethos",
        "Language whose credibility comes from its speaker's reputation or authority.",
        "I started exercising twice per week because my doctor said it would help alleviate my pain.",

        "Pathos",
        "Language that creates an emotional connection with the reader or listener.",
        "Please donate to the animal shelter. We're desperately in need of funding to help our animals, and every dollar counts.",

        "Hyperbole",
        "An extreme exaggeration meant to highlight the issue presented.",
        "I deal with thousands of angry customers every day.",

        "Meiosis",
        "The reverse of hyperbole, this emphasizes how far outside the norm an issue is through extreme understatement.",
        "Compared to others in the area, our school was empty.",

        "Epistrophe",
        "The repetition of a word through successive phrases, clauses, or sentences for the purpose of emphasizing it as a concept.",
        "Government of the people, by the people, for the people, shall not perish from the earth.",

        "Metaphor",
        "A type of figurative language that compares two topics by claiming that one literally is the other.",
        "My mother's cooking is heaven on earth.",

        "Chiasmus",
        "The repetition of a sentence with the word order switched around.",
        "Ask not what your country can do for you, ask what you can do for your country.");

    $count = 8;


    function FisherYatesIndex($n) {

        # Create array of n elements containing indexes.
        $arr = [];

        for ($i = 0; $i < $n; $i++)
            $arr[$i] = $i;

        # Perform index shuffle.
        for($i = $n - 1; $i > 0; $i--) {
            $r = rand(0, $i); $tmp = $arr[$i];
            $arr[$i] = $arr[$r];
            $arr[$r] = $tmp; }

        return $arr; }

    # Recall prior variable state
    $id = $_SESSION['id'] ?? "";					# App name.

    # Prevent data corruption to next application.
    if ($id == "rhetoric") {
        $lastCmd = $_SESSION['cmd'] ?? 0;           # User command.
        $mode = $_SESSION['mode'] ?? 0;             # Current mode.
        $ndx = $_SESSION['ndx'] ?? 0;               # Current index.
        $rndNdx = $_SESSION['rndNdx'] ?? 0;         # Random index.
        $update = $_SESSION['update'] ?? 0; }       # Time from prior page update.
    else {
        $mode = 0;
        $ndx = 0;
        $rndNdx = FisherYatesIndex($count); 
        $update = 0; }

    # Get SoftKey selection.
    $cmd = $_GET['key'] ?? 0;

    # Check page refresh delay.
    if (time() - $update < $delay)

        # Process user request based on the key pressed.	
        switch($cmd) {

        case 1 : # mode
            $mode = ++$mode % 3;
            break;

        case 2 : # previous
            if (--$ndx < 0)
                $ndx = $count - 1;

            $mode = 0;
            break;

        case 3 : # next
            if (++$ndx == $count)
                $ndx = 0;

            $mode = 0;
            break;
        }

    else {
        # Advance to next item after displaying answer.
        $ndx = ++$ndx % $count;
        $mode = 0; }

    # Update the indexes on both fields.
    $i = $rndNdx[$ndx] * 3;
    $j = $i + 1;
    $k = $i + 2;

    switch($mode) {

    case 0 : # Rhetoric
        echo "<CiscoIPPhoneText>\n";
        echo "  <Title>Rhetoric - Name</Title>\n";
        echo "  <Text>$rhetoric[$i]</Text>\n";

        $msg = "";
        break;

    case 1 : # Defined
        echo "<CiscoIPPhoneText>\n";
        echo "  <Title>Rhetoric - Defined</Title>\n";
        echo "  <Text>$rhetoric[$j]</Text>\n";

        $msg = "Use arrow keys to view all text.";
        break;

    case 2 : # Example
        echo "<CiscoIPPhoneText>\n";
        echo "  <Title>Rhetoric - Example</Title>\n";
        echo "  <Text>$rhetoric[$k]</Text>\n";

        $msg = "Use arrow keys to view all text.";
        break;
    }

    echo "  <SoftKeyItem>\n";
    echo "    <Name>More</Name>\n";
    echo "    <URL>$urlPath?key=1</URL>\n";
    echo "    <Position>1</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Prev</Name>\n";
    echo "    <URL>$urlPath?key=2</URL>\n";
    echo "    <Position>2</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Next</Name>\n";
    echo "    <URL>$urlPath?key=3</URL>\n";
    echo "    <Position>3</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Exit</Name>\n";
    echo "    <URL>SoftKey:Exit</URL>\n";
    echo "    <Position>4</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <Prompt>$msg</Prompt>\n";
    echo "</CiscoIPPhoneText>";

    # Save current variable state.
    $_SESSION['id'] = "rhetoric";
    $_SESSION['cmd'] = $cmd;
    $_SESSION['mode'] = $mode;
    $_SESSION['ndx'] = $ndx;
    $_SESSION['rndNdx'] = $rndNdx;
    $_SESSION['update'] = time();
?>
