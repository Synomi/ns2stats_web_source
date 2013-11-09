DevourEngine.convZtoCvsX = function(z)
{

    z = z / (this.data['backgroundWidth']) * this.cvs.width;

    z = (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];
    z = this.moveByZOrigin(z);

    return z;
}
DevourEngine.convOriginXtoCvsY = function(x)
{
    x = -1 * x;
    x = x / (this.data['backgroundHeight']) * this.cvs.height;

    return (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y']; //REAL Y
}
DevourEngine.convXtoCvsY = function(x)
{
    x = x * -1;
    x = x / (this.data['backgroundHeight']) * this.cvs.height;

    x = (x + this.data['plotToMapConst_y']) * this.data['plotToMapLin_Y'];

    x = this.moveByXOrigin(x);

    return x; //real Y
}
DevourEngine.convOriginZtoCvsX = function(z)
{

    z = z / (this.data['backgroundWidth']) * this.cvs.width;

    return (z + this.data['plotToMapConst_x']) * this.data['plotToMapLin_X'];
    ; //REAL X
}

DevourEngine.moveByXOrigin = function(a)
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

DevourEngine.moveByZOrigin = function(a)
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
DevourEngine.drawOrigin = function()
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
