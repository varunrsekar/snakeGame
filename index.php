<html>
<head>
<body>
<!-- Lets make a simple snake game -->
<canvas id="canvas" width="450" height="450"></canvas>

<!-- Jquery -->
<script src="jquery-2.0.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	//Canvas stuff
	var canvas = $("#canvas")[0];
	var ctx = canvas.getContext("2d");
	var w = $("#canvas").width();
	var h = $("#canvas").height();
	console.log(w);
	var obstacle_array;
	//Lets save the cell width in a variable for easy control
	var cw = 10;
	var d;
	var dir;
	var food;
	var score;
	var bx,by;
	var lvlup=0;
	var obstacle_w;
	var obstacle_h;
	//Lets create the snake now
	var snake_array; //an array of cells to make up the snake
	
	function init()
	{
		d = "right"; //default direction
		create_snake();
		create_food(); //Now we can see the food particle
		//finally lets display the score
		score = 0;
		
		//Lets move the snake now using a timer which will trigger the paint function
		//every 60ms
		//if(typeof game_loop != "undefined") clearInterval(game_loop);
		game_loop = setInterval(paint, 100);
	}
	init();
	
	function create_snake()
	{
		var length = 5; //Length of the snake
		snake_array = []; //Empty array to start with
		for(var i = length-1; i>=0; i--)
		{
			//This will create a horizontal snake starting from the top left
			snake_array.push({x: i, y:0});
		}
	}
	
	//Lets create the food now
	function create_food()
	{
		food = {
			x: Math.round(Math.random()*(w-cw)/cw), 
			y: Math.round(Math.random()*(h-cw)/cw), 
		};
		//This will create a cell with x/y between 0-44
		//Because there are 45(450/10) positions accross the rows and columns
		
		if(check_obstacle_collision(food.x,food.y,obstacle_array))
			create_food();
	}
	function create_obstacle()
	{
		obstacle = {
				x:Math.round(Math.random()*(w-cw)/cw),
				y: Math.round(Math.random()*(h-cw)/cw),
                };
	console.log(obstacle.x);
	console.log(obstacle.y);
	//	ctx.fillStyle = "blue";
          //      ctx.fillRect(bx*cw*Math.round(Math.random()*10), by*cw*Math.round(Math.random()*10), cw, cw);
            //   ctx.strokeStyle = "black";
              //  ctx.strokeRect(bx*cw*Math.round(Math.random()*10), by*cw*Math.round(Math.random()*10), cw, cw); 
	 if(w-obstacle.x>cw*10){
                        if(h-obstacle.y>cw*10){
                                for(var j=0;j<cw*10;j++)
                                        for(var k=0;k<cw*10;k++)
                                                obstacle_array.push({x : obstacle.x+j , y : obstacle.y+k});
                       		obstacle_w = j;
				obstacle_h = k;
			 }
                        else{
                                for(var j=0;j<cw*10;j++)
                                        for(var k=0;k<h-obstacle.y;k++)
                                                obstacle_array.push({x : obstacle.x+j , y : obstacle.y+k});
                		obstacle_w = j;
                                obstacle_h = k;
			}
		}
                else{
                        if(h-obstacle.y>cw*10){
                                for(var j=0;j<w-obstacle.x;j++)
                                        for(var k=0;k<cw*10;k++)
                                                obstacle_array.push({x : obstacle.x+j , y : obstacle.y+k});
                        	obstacle_w = j;
                                obstacle_h = k;
			}
                        else{
                                for(var j=0;j<w-obstacle.x;j++)
                                        for(var k=0;k<h-obstacle.y;k++)
                                                obstacle_array.push({x : obstacle.x+j , y : obstacle.y+k});
                		obstacle_w = j;
                                obstacle_h = k;	
			}
			
		}

	}
	

	
	//Lets paint the snake now
	function paint()
	{
		//To avoid the snake trail we need to paint the BG on every frame
		//Lets paint the canvas now
		ctx.fillStyle = "white";
		ctx.fillRect(0, 0, w, h);
		ctx.strokeStyle = "black";
		ctx.strokeRect(0, 0, w, h);
		
		//The movement code for the snake to come here.
		//The logic is simple
		//Pop out the tail cell and place it infront of the head cell
		var nx = snake_array[0].x;
		var ny = snake_array[0].y;
		//These were the position of the head cell.
		//We will increment it to get the new head position
		//Lets add proper direction based movement now
		if(d == "right"){
			dir=1;
			 nx++;
		}
		else if(d == "left"){
			dir=3	
			 nx--;
		}
		else if(d == "up"){
			dir=4;
			 ny--;
		}
		else if(d == "down"){ 
			dir=2;
			ny++;
		}
		
		//Lets add the game over clauses now
		//This will restart the game if the snake hits the wall
		//Lets add the code for body collision
		//Now if the head of the snake bumps into its body, the game will restart
		
		if(nx == -1 || nx == w/cw || ny == -1 || ny == h/cw || check_collision(nx,ny,snake_array))
		{
			if(nx==-1){
				snake_array.pop({x:nx,y:ny});
				nx=(w/cw)-nx-2;
		                snake_array.push({x:nx,y:ny});
		
			}
			else if(nx==w/cw){
                                snake_array.pop({x:nx,y:ny});
                                nx=(w/cw)-nx-1;
                                snake_array.push({x:nx,y:ny});

                        }
 			else if(ny==-1){
                                snake_array.pop({x:nx,y:ny});
                                ny=(h/cw)-ny-2;
      		                snake_array.push({x:nx,y:ny});


                        }
			else if(ny==h/cw){
                                snake_array.pop({x:nx,y:ny});
                                ny=(h/cw)-ny-1;
                                snake_array.push({x:nx,y:ny});


                        }
			else{
			//Restart game
			init();
			//Lets organize the code a bit now.
			return;
			}
			
			
			
		}
		
		if(check_obstacle_collision(snake_array[0].x,snake_array[0].y,obstacle_array)){
			init();
			return;
		}
		//Lets write the code to make the snake eat the food
		//The logic is simple
		//If the new head position matches with that of the food,
		//Create a new head instead of moving the tail
		if(nx == food.x && ny == food.y)
		{
			var tail = {x: nx, y: ny};
			score++;
			//Create new food
			create_food();
			if(score==10) lvlup++;	
			if(score%10==0){
				
				create_snake();
				create_obstacle();
			}
		}
		else
		{
			var tail = snake_array.pop(); //pops out the last cell
			tail.x = nx; tail.y = ny;
		}
		//The snake can now eat the food.
		
		snake_array.unshift(tail); //puts back the tail as the first cell
		
		for(var i = 0; i < snake_array.length; i++)
		{
			var c = snake_array[i];
			//Lets paint 10px wide cells
			paint_cell(c.x, c.y);
		}
		if(lvlup!=0) paint_obstacle(obstacle.x,obstacle.y);
		//Lets paint the food
		paint_cell(food.x, food.y);
		//Lets paint the score
		var score_text = "Score: " + score;
		ctx.fillText(score_text, 5, h-5);
	}
	
	//Lets first create a generic function to paint cells
	function paint_cell(x, y)
	{
		ctx.fillStyle = "blue";
		ctx.fillRect(x*cw, y*cw, cw, cw);
		ctx.strokeStyle = "white";
		ctx.strokeRect(x*cw, y*cw, cw, cw);
	}
	function paint_obstacle(ox,oy){
		   
                ctx.fillStyle = "blue";
                ctx.fillRect(ox*cw, oy*cw, cw*10, cw*10);
               ctx.strokeStyle = "black";
                ctx.strokeRect(ox*cw, oy*cw, cw*10, cw*10);
		
		/*if(w-ox>cw*10){
			if(h-oy>cw*10){
				for(var j=0;j<cw*10;j++)
					for(var k=0;k<cw*10;k++)
						obstacle_array[j][k] = {x : ox+j , y : oy+k};
			}
			else
				for(var j=0;j<cw*10;j++)
                                        for(var k=0;k<h-oy;k++)
                                                obstacle_array[j][k] = {x : ox+j , y : oy+k};
		}
		else{
			if(h-oy>cw*10){
                                for(var j=0;j<w-ox;j++)
                                        for(var k=0;k<cw*10;k++)
                                                obstacle_array[j][k] = {x : ox+j , y : oy+k};
                        }
                        else
                                for(var j=0;j<w-ox;j++)
                                        for(var k=0;k<h-oy;k++)
                                                obstacle_array[j][k] = {x : ox+j , y : oy+k};
		}*/
	console.log(obstacle_array);
	}
	function check_obstacle_collision(x,y,array){
	
	
			 for(var i = 0; i < obstacle_w; i++)
                {
                        for(var j=0;j<obstacle_h;j++)
			if(array[i][j].x == x && array[i][j].y == y)
                         return true;
                }
                return false;
		
	}
	function check_collision(x, y, array)
	{
	

		//This function will check if the provided x/y coordinates exist
		//in an array of cells or not
		for(var i = 0; i < array.length; i++)
		{
			if(array[i].x == x && array[i].y == y)
			 return true;
		}
		return false;
	}
	
	//Lets add the keyboard controls now
	$(document).keydown(function(e){
		var key = e.which;
		if(key=="17") d = "shoot";
		//We will add another clause to prevent reverse gear
		if(key == "37" && d != "right") d = "left";
		else if(key == "38" && d != "down") d = "up";
		else if(key == "39" && d != "left") d = "right";
		else if(key == "40" && d != "up") d = "down";
		//The snake is now keyboard controllable
	})
	
	
	
	
	
	
	
})
</script>
</body>
</head>
</html>
