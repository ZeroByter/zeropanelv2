<center>
	<h1>No permission to view this page.</h1>
	<h3>This could be for a number of reasons, but we aren't exactly sure why.</h3>
	<br><br>
	Well. In the mean time, lets see how high can we count really really fast!<br>
	<span id="number"></span> in <span id="time">1</span> seconds.
	<br><br><br>
	Go back to <a href="/">the home page</a>.
</center>

<script>
	var number = 0
	var time = 1

	setInterval(function(){
		number += 2
		$("#number").html(number)
	}, 0)
	setInterval(function(){
		time += 1
		$("#time").html(time)
	}, 1000)
</script>
