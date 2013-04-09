function Minimap_object(canvasElement, map, data)
{
    //object
    var self = this;
    this.hits = new Array();
    this.deaths = new Array();
    this.data = JSON.parse(data);
    //background
    this.backgroundLoaded=false;
    this.mapName = map;
    //minimap
    this.minimapLoaded=false;

    //canvas
    this.cvs = canvasElement;
    try
    {
        this.ctx = this.cvs.getContext('2d');
    }
    catch(ex)
    {
        alert("Error: " + ex);
        return false;
    }

    //size
    this.cvs.width = $('#minimap').width();
    this.cvs.height = $('#minimap').height();

    //load background
    this.background = new Image();
    this.background.src= "/images/minimaps/tempbg.jpg";

    $(this.background).load(function() {
        self.backgroundLoaded=true;
        self.draw();
    });

    this.minimap = new Image();
    
    this.minimap.src= "/images/minimaps/" + map + ".png";
        
    $(this.minimap).load(function() {
        self.minimapLoaded=true;
        self.draw();
    });
    this.clear = function()
    {
        self.ctx.fillStyle    = "#000";
        self.ctx.beginPath();
        self.ctx.rect(0,0,self.cvs.width,self.cvs.height);
        self.ctx.closePath();
        self.ctx.fill();
    }

    this.drawDeath = function(death)
    {
        self.ctx.fillStyle    = 'rgba(255,0,0,0.2)';
        self.ctx.beginPath();
        self.ctx.arc(this.convZtoCvsX(death.target_z),this.convXtoCvsY(death.target_x),8,0,2*Math.PI);
        self.ctx.closePath();
        self.ctx.fill();

    }
    // Draw a pixel on the canvas
    this.point = function  (x, y)
    {
        // Calculate the pixel offset from the coordinates
        var id = self.ctx.createImageData(1,1); // only do this once per page
        var d  = id.data;                        // only do this once per page
        d[0]   = 255;
        d[1]   = 200;
        d[2]   = 200;
        d[3]   = 1;
        self.ctx.putImageData( id, x, y );
    }
    this.addHit = function (data)
    {        
        this.hits[this.hits.length] = data;        
    }
    this.addDeath = function (data)
    {
        this.deaths[this.deaths.length] = data;        
    }
    this.onMouseClick = function(e)
    {
        if (!self.readyToDraw()) return
        var x=e.clientX-self.cvs.offsetLeft+window.pageXOffset;
        var y=e.clientY-self.cvs.offsetTop+window.pageYOffset;

        self.lastX=x;
        self.lastY=y;


    }

    this.onMouseMove = function(e)
    {
        if (!self.readyToDraw()) return
        var x=e.clientX-self.cvs.offsetLeft+window.pageXOffset;
        var y=e.clientY-self.cvs.offsetTop+window.pageYOffset;
        self.lastX=x;
        self.lastY=y;

    //  self.point(x,y);
    }


    //event listeners
    this.cvs.addEventListener("mousemove",this.onMouseMove, false);
    this.cvs.addEventListener("click",this.onMouseClick, false);

    this.draw = function()
    {
        if (!self.readyToDraw()) return

        self.ctx.drawImage(self.background,0,0,this.cvs.width,this.cvs.height);
        //self.ctx.save();
        //self.ctx.rotate(Math.PI);
        self.ctx.drawImage(self.minimap,0,0,this.cvs.width,this.cvs.height);
        //self.ctx.restore();
        self.ctx.translate(self.ctx.width/2, self.ctx.height/2);

        this.drawLines();
//        self.drawOrigin();
    }
    this.drawLines = function()
    {
        this.hits.forEach(function(hit)
        {
            self.drawLine(hit.attacker_x, hit.attacker_z, hit.target_x, hit.target_z, hit.attacker_weapon_id);
        });
        this.deaths.forEach(function(death)
        {
            //self.drawLine(death.attacker_x, death.attacker_z, death.target_x, death.target_z, death.attacker_weapon_id);
            self.drawDeath(death);
        });
    }
    this.readyToDraw = function()
    {
        if (!self.backgroundLoaded) return false;
        if (!self.minimapLoaded) return false;

        return true;
    }

    this.convZtoCvsX = function (z)
    {

        z = z/(this.data['backgroundWidth']) * this.cvs.width;

        z = (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];
        z = this.moveByZOrigin(z);

        return z;
    }
    this.convOriginXtoCvsY = function (x)
    {
        x = -1*x;
        x = x/(this.data['backgroundHeight']) * this.cvs.height;

        return (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y']; //REAL Y
    }
    this.convXtoCvsY = function (x)
    {
        x = x*-1;
        x = x/(this.data['backgroundHeight']) * this.cvs.height;

        x = (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y'];

        x = this.moveByXOrigin(x);

        return x; //real Y
    }
    this.convOriginZtoCvsX = function (z)
    {

        z = z/(this.data['backgroundWidth']) * this.cvs.width;

        return (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];; //REAL X
    }

    this.moveByXOrigin = function(a)
    {
        var dif;

        if (this.convOriginXtoCvsY(this.data['originX'])<(this.cvs.height/2))
        {
            dif = (this.cvs.height/2) - this.convOriginXtoCvsY(this.data['originX'])
            a+=dif;
        }
        else
        {
            dif = this.convOriginXtoCvsY(this.data['originX']) - (this.cvs.height/2)
            a-=dif;

        }

        return a;
    }

    this.moveByZOrigin = function(a)
    {
        var dif;

        if (this.convOriginZtoCvsX(this.data['originZ'])<(this.cvs.width/2))
        {
            dif = (this.cvs.width/2) - this.convOriginZtoCvsX(this.data['originZ'])
            a+=dif;
        }
        else
        {
            dif = this.convOriginZtoCvsX(this.data['originZ']) - (this.cvs.width/2)
            a-=dif;

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
        this.ctx.moveTo(this.convZtoCvsX(this.data['originZ']-5),this.convXtoCvsY(this.data['originX']-5));
        this.ctx.lineTo(this.convZtoCvsX(this.data['originZ']+5),this.convXtoCvsY(this.data['originX']+5));
        this.ctx.stroke();
        this.ctx.beginPath();
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 10;
        this.ctx.moveTo(this.convZtoCvsX(this.data['originZ']-5),this.convXtoCvsY(this.data['originX']+5));
        this.ctx.lineTo(this.convZtoCvsX(this.data['originZ']+5),this.convXtoCvsY(this.data['originX']-5));
        this.ctx.stroke();


    }

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

    this.drawLine = function(fX,fZ,tX,tZ,c)
    {

        var color = this.colors[c];

        this.ctx.beginPath();
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 1;
        this.ctx.moveTo(this.convZtoCvsX(fZ),this.convXtoCvsY(fX));
        this.ctx.lineTo(this.convZtoCvsX(tZ),this.convXtoCvsY(tX));        
        this.ctx.stroke();
        this.ctx.closePath();
        
    }

}