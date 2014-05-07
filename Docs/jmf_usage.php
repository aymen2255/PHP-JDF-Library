<?php
$pageTitle = 'JMF Usage';
include('inc/header.php');
?>
    <div class="row">
        <div class="col-md-8">
            <div class="row">

                <div class="col-md-12">
                    <h2><?php echo $pageTitle; ?></h2>

                    <p>
                        The JMF object allows you to create a JDF message to a print device to obtain information or send instructions.
                    </p>

                    <h3>Get basic status information</h3>

                    <section>

                        <h4 id="get_status">getStatus()</h4>

                        <p>Allow the class to analyze the local file you're building the JDF for.</p>
                        <pre>void getStatus([bool $detailed])</pre>
                        <pre><code class="php">$JMF->getStatus(true);</code></pre>

                        <ul>
                            <li>Determine if you want to send the detailed or the basic status call by sending a true or false for the $detailed parameter. Default is false.</li>
                        </ul>

                    </section>

                </div>

            </div>
        </div>
        <div class="col-md-4">

            <h3>Quick Jump</h3>

            <ul class="nav nav-pills nav-stacked">
                <li><a href="#get_status">getStatus()</a></li>
            </ul>

        </div>
    </div>
<?php include('inc/footer.php'); ?>