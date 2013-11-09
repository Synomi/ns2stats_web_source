/*
 * Devour engine core
 * V 0.01
 * 
 * Tasks:
 * 1. Get json data continously into buffer asyncroniously
 * 2. Enable playback controls for data (play, stop, rewing, forward, speedmultiplier)
 * 3. Draw data frame by frame, data can be drawn with lot higher framerate than its received, default ~30-60. 
 * 4. Draw optional heatmal using WebGl with high frame rate.
 * 
 * buffer = framebuffer, containing every frame and actor data.
 */

function DevourEngine(canvasElement, heatmapOverlay, interactiveOverlay, config)
{
    /* main */
    var self = this;
    this.fpr = 0.25; // Frame Playback Rate    
    this.frame = null;
    this.lastFrame = null;
    this.data = config.data; //minimap data
    this.map = config.map;
    
//    /* general */
//    canvasElement.style.cursor = 'none';
//    interactiveOverlay.style.cursor = 'none';

    /* minimap image */
    this.minimapLoaded = false;
    this.minimap = new Image();
    this.minimap.src = "/images/minimaps/" + this.map + ".png";

    $(this.minimap).load(function() {
        self.minimapLoaded = true;
    });


    /* buffer */
    this.buffer = new Array();
    self.buffer.movement = new Array();
    self.buffer.entity = new Array();
    self.buffer.state = null;

    /* heatmapOverlay */
    this.heatmap = heatmapOverlay;
    this.intensity = 0.01;
    this.pointSize = 70;

    /* interactiveOverlay */
    this.iO = interactiveOverlay;

    /* mouse interactions */
    this.lastX = 0;
    this.lastY = 0;
    this.lastXC = 0;
    this.lastYC = 0;

    /* actors temp*/
    this.actor = new Array();

    /* sprites*/

    this.actorSprite = new Image();
    this.actorSpriteLoaded = false;

    this.actorSprite.src = "/images/devour/minimap_blip.png";
    this.sprites = new Array();

    this.test = null;
    this.initSprites = function()
    {
        //line1
        self.sprites['techpoint'] = self.createSprite(0, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['rtpoint'] = self.createSprite(32, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['door1'] = self.createSprite(32 * 2, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['door2'] = self.createSprite(32 * 3, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['door3'] = self.createSprite(32 * 4, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['line'] = self.createSprite(32 * 5, 0, 32, 32, self.actorSprite, 0, 0, 0);
        self.sprites['powernode'] = self.createSprite(32 * 6, 64, 32, 32, self.actorSprite, 0, 0, 0);

        //line2
        self.sprites['marine'] = self.createSprite(0, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['exo'] = self.createSprite(32, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['jp'] = self.createSprite(32 * 2, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['mac'] = self.createSprite(32 * 3, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['cc1'] = self.createSprite(32 * 4, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['cc2'] = self.createSprite(32 * 5, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['cc3'] = self.createSprite(32 * 6, 32, 32, 32, self.actorSprite, 0, 0, 200);
        self.sprites['x1'] = self.createSprite(32 * 7, 32, 32, 32, self.actorSprite, 0, 100, 200);

        //line 3
        self.sprites['skulk'] = self.createSprite(0, 64, 32, 32, self.actorSprite, 255, 227, 0);
        self.sprites['gorge'] = self.createSprite(32 * 2, 64, 32, 32, self.actorSprite, 255, 227, 0);
        self.sprites['lerk'] = self.createSprite(32 * 3, 64, 32, 32, self.actorSprite, 255, 17, 0);
        self.sprites['fade'] = self.createSprite(32 * 4, 64, 32, 32, self.actorSprite, 255, 77, 0);
        self.sprites['onos'] = self.createSprite(32 * 5, 64, 32, 32, self.actorSprite, 255, 127, 0);
        self.sprites['drifter'] = self.createSprite(32 * 6, 64, 32, 32, self.actorSprite, 255, 127, 0);
        self.sprites['hivec'] = self.createSprite(32 * 7, 64, 32, 32, self.actorSprite, 255, 127, 0);
        self.sprites['x2'] = self.createSprite(32 * 8, 64, 32, 32, self.actorSprite, 255, 127, 0);
//
//        self.sprites['marine'] = self.createSprite(32 * 2, 64, 32, 32, self.actorSprite, 255, 127, 0);
//        self.sprites['marine'] = self.createSprite(32 * 2, 64, 32, 32, self.actorSprite, 255, 127, 0);
//        self.sprites['marine'] = self.createSprite(32 * 2, 64, 32, 32, self.actorSprite, 255, 127, 0);

    };


    this.spin = 0;
    this.lastRad = 0;

    this.testsprites = function()
    {
        var _radians = Math.atan2(self.lastY - self.lastYC, self.lastX - self.lastXC);
        self.spin += 1.2;
        if (self.spin > 360)
            self.spin = 0;
        var x, y;
        x = 0;
        y = 20;
        for (var i in self.sprites)
        {

            if (x > 32 * 6)
            {
                x = 0;
                y += 40;
            }
            self.ctx.save();
            
            // set screen position
            self.ctx.translate(20 + x, y);
            // set rotation

            if (self.lastXC !== 0)
                self.ctx.rotate(_radians);
            else
                self.ctx.rotate((360 - self.spin) * Math.PI / 180);

            self.ctx.drawImage(self.sprites[i], -12, -12, 24, 24);
            self.ctx.restore();
            x += 32;
        }
        self.lastRad = _radians;
    };

    $(this.actorSprite).load(function() {

        self.initSprites();
        self.draw();
        self.actorSpriteLoaded = true;

    });


    /* main canvas */
    this.cvs = canvasElement;

    try
    {
        this.ctx = this.cvs.getContext('2d');
//        this.iOctx = this.iO.getContext('2d');
    }
    catch (ex)
    {
        alert("Error: " + ex);
        return false;
    }

    this.cvs.width = $(canvasElement).width();
    this.cvs.height = $(canvasElement).height();

    /* background */
    this.backgroundLoaded = false;


    this.background = new Image();
    this.background.src = "/images/minimaps/tempbg.jpg";

    $(this.background).load(function() {
        self.backgroundLoaded = true;
        self.draw();
    });

    this.clear = function()
    {
//        self.ctx.fillStyle = "#000";
//        self.ctx.beginPath();
//        self.ctx.rect(0, 0, self.cvs.width, self.cvs.height);
//        self.ctx.closePath();
//        self.ctx.fill();

    };

    this.update = function()
    {
        self.updateActors();
        self.updateMovement();
    };


    // Draw a pixel on the canvas
    this.pointId = self.ctx.createImageData(1, 1); // only do this once per page
    this.pointData = this.pointId;
    this.point = function(x, y)
    {
        // Calculate the pixel offset from the coordinates
        // only do this once per page
        self.pointData[0] = 255;
        self.pointData[1] = 200;
        self.pointData[2] = 200;
        self.pointData[3] = 1;
        self.ctx.putImageData(self.pointData, x, y);
    }

    this.onMouseClick = function(e)
    {
        if (!self.readyToDraw())
            return;

        var mousePos = self.getMousePos(self.iO, e);

        var x = mousePos.x;
        var y = mousePos.y;

        self.lastXC = x;
        self.lastYC = y;


//        for (var i = 0; i < 600; i++)
//        {
//            self.heatmap.addPoint((x - 400 + 800 * Math.random()), (y - 400 + 800 * Math.random()), self.pointSize, self.intensity * 20);
//        }
    }
    this.getMousePos = function(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }
    this.onMouseMove = function(e)
    {
        if (!self.readyToDraw())
            return;
        var mousePos = self.getMousePos(self.iO, e);

        var x = mousePos.x;
        var y = mousePos.y;
        self.lastX = x;
        self.lastY = y;

        //self.point(x, y);
//        for (var i = 0; i < 200; i++)
//        {
//            self.heatmap.addPoint((x - 100 + 200 * Math.random()), (y - 100 + 200 * Math.random()), self.pointSize, self.intensity);
//        }
    };


    //event listeners
    this.iO.addEventListener("mousemove", this.onMouseMove, false);
    this.iO.addEventListener("click", this.onMouseClick, false);

    this.draw = function()
    {
        if (!self.readyToDraw())
            return;

        self.ctx.drawImage(self.background, 0, 0, self.cvs.width, self.cvs.height);
        self.ctx.drawImage(self.minimap, 0, 0, this.cvs.width, this.cvs.height);
        //self.ctx.drawImage(self.actorSprite, 50, 50, 256, 256);
        //self.ctx.drawImage(self.actorSprite, 0, 0, 32, 32, 102, 568, 32, 32);
        //self.ctx.save();
        //self.ctx.rotate(Math.PI);
        //self.ctx.restore();
        //self.ctx.translate(self.ctx.width / 2, self.ctx.height / 2);
        self.testsprites();
        self.drawActors();

        //test 1203
        self.ctx.save();

        // set screen position

        self.ctx.translate(20 + self.lastXC - 18, self.lastYC - 8);
        // set rotation

        // Radians for the canvas rotate method.
        var _radians = Math.atan2(self.lastY - self.lastYC, self.lastX - self.lastXC);
        //var rotation = Math.atan2(self.lastX, self.lastY);
        self.ctx.rotate(_radians);
        self.ctx.drawImage(self.sprites['fade'], -12, -12, 24, 24);
        self.ctx.restore();
        
        self.ctx.drawImage(self.sprites['x1'], self.lastX-12, self.lastY-12, 24, 24);
    };
    this.playtest = function()
    {
        //sets frame to smallest and attemps to playback player movement
        self.frame = null;
        self.lastFrame = null;
        for (var i = 0; i < 100000; i++)
            if (self.buffer.entity[i] !== undefined)
            {
                if (self.frame === null)
                    self.frame = i;

                self.lastFrame = i;
            }

        console.log('first frame: ' + self.frame + ' last frame: ' + self.lastFrame);
    };

    this.drawActors = function()
    {
        if (self.actor.length === 0)
            return;

        for (var i in self.actor)
        {
            var p = self.actor[i];
            if (p.health > 0 && p.lifeform !== 'dead')
            {
                //self.ctx.fillStyle = 'rgba(255,0,0,0.2)';
                //self.ctx.beginPath();
                //self.ctx.arc(this.convZtoCvsX(p.z), this.convXtoCvsY(p.x), 30, Math.PI / 2 + p.wrh, Math.PI / 16 + p.wrh);            
                //self.ctx.closePath();
                //self.ctx.fill();
                self.ctx.save();

                // set screen position
                self.ctx.translate(this.convZtoCvsX(p.z), this.convXtoCvsY(p.x));
                // set rotation
                self.ctx.rotate((360 - p.wrh) * Math.PI / 180);
                if (parseInt(p.team) === 1)
                {
                    //self.ctx.drawImage(self.actorSprite, 0, 32, 32, 32, this.convZtoCvsX(p.z), this.convXtoCvsY(p.x), 24, 24);

                    //self.ctx.drawImage(self.actorSprite, 0, 32, 32, 32, -12, -12, 24, 24);                                        
                    self.ctx.drawImage(self.sprites['exo'], -12, -12, 24, 24);
                    //self.ctx.putImageData(self.sprites['marine'], 0, 0);
                    self.ctx.restore();
                    self.ctx.fillStyle = "blue";
                }
                if (parseInt(p.team) === 2)
                {
                    //self.ctx.drawImage(self.actorSprite, 0, 64, 32, 32, this.convZtoCvsX(p.z), this.convXtoCvsY(p.x), 24, 24);
                    //self.ctx.drawImage(self.actorSprite, 0, 64, 32, 32, -12, -12, 24, 24);
                    self.ctx.drawImage(self.sprites['skulk'], -12, -12, 24, 24);
                    self.ctx.restore();
                    self.ctx.fillStyle = "orange";
                }


                self.ctx.font = "7pt bold   sans-serif";
                self.ctx.fillText(p.name, this.convZtoCvsX(p.z) - 10, this.convXtoCvsY(p.x) + 30);
                self.ctx.font = "7pt   sans-serif";
                self.ctx.fillStyle = "green";
                self.ctx.fillText(p.health, this.convZtoCvsX(p.z) - 10, this.convXtoCvsY(p.x) + 39);
                self.ctx.fillStyle = "cyan";
                self.ctx.fillText(p.armor, this.convZtoCvsX(p.z) + 10, this.convXtoCvsY(p.x) + 39);
                // self.ctx.strokeText(p.wrh, this.convZtoCvsX(p.z) - 10, this.convXtoCvsY(p.x) + 50);

            }
        }
    }
    this.updateActors = function()
    {

        if (self.buffer.entity[self.frame] !== 'undefined')
        {
            var players = self.buffer.entity[self.frame];
            for (var n in players)
            {
                self.actor[n] = players[n];
            }
        }

        //for (var i =0;i<players.length;i++)
        //  console.log(players[i]);

    };
    this.updateMovement = function()
    {
        //draws all players in current frame

        if (self.buffer.movement[self.frame] !== 'undefined')
        {
            var players = self.buffer.movement[self.frame];
            for (var i in players)
            {
                var p = players[i];
                self.actor[i].x = p.x;
                self.actor[i].z = p.z;
                self.actor[i].y = p.y;
                self.actor[i].wrh = p.wrh;
                self.actor[i].id = p.id;
            }
        }

        //calculate smooted position
        if (self.buffer.movement[self.frame + 1] !== 'undefined')
        {
            var nextPlayers = self.buffer.movement[self.frame + 1];
            var lx, ly, lz, lwrh, next, nwrh;
            var timeAfterFrame = self.microtime(true) - self.lastTime;
            var percentToNextFrame = (timeAfterFrame / self.fpr);
            for (var i in nextPlayers)
            {
                //current
                lx = self.actor[i].x;
                ly = self.actor[i].y;
                lz = self.actor[i].z;
                lwrh = self.actor[i].wrh;

                //next
                next = nextPlayers[i];
                nwrh = next.wrh;
                //how far is next update in percentual value
                //x oli 3 ja nyt on 6 ja 50% menny joten 4.5 pitäs tulla
                //3-6 = -3 *0.5 = 1.5 + x = 4.5

                //jos vanha on pienempi kuin uusi ja uuden-vanhan ero on yli 180
                if (lwrh < nwrh && (nwrh - lwrh) > 180)
                {
                    //uusi on esim 350 ja vanha on 10
                    //näiden ero on 360-350-10 = 20

                    //eli kohdassa 50% pitäisi tulla arvoksi 360 astetta tai 0 astetta eli
                    //350+(ero*percent) = 360
                    self.actor[i].wrh = nwrh + ((360 - nwrh - lwrh) * percentToNextFrame);

                }
                else if (lwrh > nwrh && (lwrh - nwrh) > 180)
                {
                    self.actor[i].wrh = lwrh + ((360 - nwrh - lwrh) * percentToNextFrame);
                }
                else
                    self.actor[i].wrh += (nwrh - lwrh) * percentToNextFrame;

                //jos uusi on pienempi kuin vanha = tarkoittaa et 360 menee 0:n puolelle
//                
//                if (nwrh - lwrh > 180)
//                {
//                    self.actor[i].wrh -= 360+(nwrh - lwrh) * percentToNextFrame;
//                }
//                else if (nwrh - lwrh < -180)
//                {
//                    self.actor[i].wrh -= 170+(nwrh - lwrh) * percentToNextFrame;
//                }
//                else
//                    self.actor[i].wrh += (nwrh - lwrh) * percentToNextFrame;
//                if (nwrh < 90 && lwrh > 270)
//                {
//                    self.actor[i].wrh += ((nwrh - lwrh) * percentToNextFrame);
//                }
//                else
                //self.actor[i].wrh += (nwrh - lwrh) * percentToNextFrame;



                self.actor[i].x += (next.x - lx) * percentToNextFrame;

//                if (self.actor[i].y>ly)
//                    self.actor[i].y += Math.abs(next.y-ly)*percentToNextFrame;
//                else
//                    self.actor[i].y -= Math.abs(next.y-ly)*percentToNextFrame;                                

                //jos seuraava x on suurempi kuin nykyinen x
//                if (self.actor[i].z > lz)
//                    self.actor[i].z += (next.z - lz) * percentToNextFrame;
//                else
                self.actor[i].z -= (lz - next.z) * percentToNextFrame;

            }
        }
        //for (var i =0;i<players.length;i++)
        //  console.log(players[i]);

    };
    this.readyToDraw = function()
    {
        if (!self.backgroundLoaded)
            return false;

        if (!self.minimapLoaded)
            return false;

        if (!self.actorSpriteLoaded)
            return false;


        return true;
    };



    this.colors = [
        "grey", //0
        "rgba(0,0,0,0.8)", //1
        "rgba(0,0,100,0.8)",
        "rgba(0,0,200,0.8)",
        "rgba(225,66,0,0.8)",
        "rgba(0,0,205,0.8)", //5
        "rgba(0,0,55,0.8)",
        "rgba(155,66,0,0.8)",
        "rgba(155,36,0,0.8)",
        "rgba(0,0,115,0.8)",
        "transparent", //10
        "rgba(0,0,235,0.8)",
        "transparent",
        "rgba(115,36,0,0.8)",
        "transparent",
        "transparent", //15
        "rgba(255,66,0,0.8)",
        "rgba(125,66,0,0.8)",
        "transparent",
        "rgba(255,66,0,0.8)",
        "transparent", //20
        "transparent",
        "orange",
        "rgba(245,66,0,0.8)",
        "rgba(255,46,0,0.8)",
        "transparent", //25
        "transparent",
        "transparent",
        "transparent",
        "rgba(0,0,230,0.8)",
        "rgba(0,0,255,0.8)", //30
        "rgba(0,0,145,0.8)",
        "transparent",
        "transparent",
        "transparent",
        "transparent", //35
        "transparent",
        "transparent",
        "transparent",
        "transparent",
        "trasnparent", //40
        "transparent",
        "transparent",
        "transparent",
        "transparent",
        "transparent", //45
        "transparent",
        "transparent",
        "transparent",
        "transparent",
        "rgba(0,0,255,0.8)", //50
        "transparent",
        "trasnparent",
        "transparent",
        "purple",
        "purple", //55
        "purple",
        "purple",
        "purple",
        "purple",
        "purple",
    ];

    this.drawLine = function(fX, fZ, tX, tZ, c)
    {

        var color = this.colors[c];

        this.ctx.beginPath();
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 1;
        this.ctx.moveTo(this.convZtoCvsX(fZ), this.convXtoCvsY(fX));
        this.ctx.lineTo(this.convZtoCvsX(tZ), this.convXtoCvsY(tX));
        this.ctx.stroke();
        this.ctx.closePath();

    }
    this.microtime = function(get_as_float)
    {
        var unixtime_ms = new Date().getTime();
        var sec = parseInt(unixtime_ms / 1000);
        return get_as_float ? (unixtime_ms / 1000) : (unixtime_ms - (sec * 1000)) / 1000 + ' ' + sec;
    }

    this.loop = function()
    {
        if (!self.readyToDraw())
        {
            console.log('loading..')
            return;
        }
        /* canvas */
        self.clear();
        self.update();
        self.draw();


        /* heatmapOverlay */
        if (self.heatmap !== null)
        {
            //self.heatmap.clear();
            self.heatmap.multiply(0.995)
            self.heatmap.update();
            self.heatmap.display();
        }

        if (self.frame !== null && self.frame <= self.lastFrame)
        {
            if (self.microtime(true) - self.fpr > self.lastTime)
            {
                self.lastTime = self.microtime(true);
                self.frame++;

            }
        }
        self.frameCount++;
    };
    this.frameCount = 0;
    this.lastTime = this.microtime(true);
    this.debugLoop = function()
    {
        //var mtime = self.microtime(true);
        document.title = 'FPS:' + self.frameCount;
        self.frameCount = 0;
    };


    document.title = this.microtime(true);
    this.timer = setInterval(function() {
        self.loop();
    }, this.fpr * 60);

    this.addToBuffer = function(data)
    {
        var first = 0;
        for (var i in data.Movement)
        {

            if (data.Movement.hasOwnProperty(i)) {
                if (first === 0)
                    first = i;
                self.buffer.movement[i] = data.Movement[i];
            }
        }
        for (var i in data.Entity)
        {
            if (data.Entity.hasOwnProperty(i)) {
                self.buffer.entity[i] = data.Entity[i];
            }
        }

        self.buffer.state = data.state;
        self.frame = first;
    }

    this.fetchNewData = function()
    {
        console.log('fetch start');
        $.getJSON("http://dev.ns2stats.com/api/getdevour", function(data) {
            console.log('fetch adding data');
            console.log(data);
            self.addToBuffer(data);
            console.log('fetch complete');
            devour.playtest();
        });
    }

    this.fetchNewData();

    this.fetchTimer = setInterval(function() {
        self.fetchNewData();
    }, 29000);

    this.debugTimer = setInterval(function() {
        self.debugLoop();
    }, 1000);
    console.log('Devour loaded');


    this.convZtoCvsX = function(z)
    {

        z = z / (this.data['backgroundWidth']) * this.cvs.width;

        z = (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];
        z = this.moveByZOrigin(z);

        return z;
    }
    this.convOriginXtoCvsY = function(x)
    {
        x = -1 * x;
        x = x / (this.data['backgroundHeight']) * this.cvs.height;

        return (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y']; //REAL Y
    }
    this.convXtoCvsY = function(x)
    {
        x = x * -1;
        x = x / (this.data['backgroundHeight']) * this.cvs.height;

        x = (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y'];

        x = this.moveByXOrigin(x);

        return x; //real Y
    }
    this.convOriginZtoCvsX = function(z)
    {

        z = z / (this.data['backgroundWidth']) * this.cvs.width;

        return (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];
        ; //REAL X
    }

    this.moveByXOrigin = function(a)
    {
        var dif;

        if (this.convOriginXtoCvsY(this.data['originX']) < (this.cvs.height / 2))
        {
            dif = (this.cvs.height / 2) - this.convOriginXtoCvsY(this.data['originX'])
            a += dif;
        }
        else
        {
            dif = this.convOriginXtoCvsY(this.data['originX']) - (this.cvs.height / 2)
            a -= dif;

        }

        return a;
    }

    this.moveByZOrigin = function(a)
    {
        var dif;

        if (this.convOriginZtoCvsX(this.data['originZ']) < (this.cvs.width / 2))
        {
            dif = (this.cvs.width / 2) - this.convOriginZtoCvsX(this.data['originZ'])
            a += dif;
        }
        else
        {
            dif = this.convOriginZtoCvsX(this.data['originZ']) - (this.cvs.width / 2)
            a -= dif;

        }

        return a;
    }
    this.drawOrigin = function()
    {
        var color = this.colors[this.colorIndex++];

        if (this.colorIndex >= this.colors.length) {
            this.colorIndex = 0;
        }

        this.ctx.beginPath();
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 10;
        this.ctx.moveTo(this.convZtoCvsX(this.data['originZ'] - 5), this.convXtoCvsY(this.data['originX'] - 5));
        this.ctx.lineTo(this.convZtoCvsX(this.data['originZ'] + 5), this.convXtoCvsY(this.data['originX'] + 5));
        this.ctx.stroke();
        this.ctx.beginPath();
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 10;
        this.ctx.moveTo(this.convZtoCvsX(this.data['originZ'] - 5), this.convXtoCvsY(this.data['originX'] + 5));
        this.ctx.lineTo(this.convZtoCvsX(this.data['originZ'] + 5), this.convXtoCvsY(this.data['originX'] - 5));
        this.ctx.stroke();


    }

    this.createSprite = function(x, y, width, height, img, r, g, b) {
        var c = document.createElement('canvas');
        c.width = width;
        c.height = height;
        var ctx = c.getContext('2d');
        ctx.drawImage(img, -x, -y);
        var imgData = ctx.getImageData(0, 0, c.width, c.height);
        for (var i = 0; i < imgData.data.length; i += 4)
        {
            imgData.data[i] = r | imgData.data[i];
            imgData.data[i + 1] = g | imgData.data[i + 1];
            imgData.data[i + 2] = b | imgData.data[i + 2];
        }
        ctx.putImageData(imgData, 0, 0);

        return c;
    };

    this.drawSprite = function(imageObject, x, y, rotation, scale)
    {
        var w = imageObject.width;
        var h = imageObject.height;

        // save state
        ctx.save();
        // set screen position
        ctx.translate(x, y);
        // set rotation
        ctx.rotate(rotation);
        // set scale value
        ctx.scale(scale, scale);
        // draw image to screen drawImage(imageObject, sourceX, sourceY, sourceWidth, sourceHeight,
        // destinationX, destinationY, destinationWidth, destinationHeight)
        ctx.drawImage(imageObject, 0, 0, w, h, -w / 2, -h / 2, w, h);
        // restore state
        ctx.restore();
    }
}