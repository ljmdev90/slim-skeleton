<h1>This is a test template!</h1>
$a = <?php echo $a; ?>;<br />
$b = <?php echo $b; ?>;<br />
$data = <?php var_export($data); ?>;<br />
<h2></h2>

<script>
    var ws = new WebSocket("ws://192.168.50.100:9501");

    ws.onopen = function(evt) { 
        console.log("Connection open ..."); 
        ws.send("Hello WebSockets!");
    };

    ws.onmessage = function(evt) {
        console.log( "Received Message: " + evt.data);
        document.getElementsByTagName('h2')[0].innerText = evt.data;
        // ws.close();
    };

    ws.onclose = function(evt) {
        console.log("Connection closed.");
    };      
</script>