<script type="text/javascript" >
    //<![CDATA[
    
    function showUrlInDialog(url){
        var tag = $("<div></div>");
        $.ajax({
            url: url,
            success: function(data) {
                tag.html(data).dialog({modal: false,height:500,width:800}).dialog('open');
            }
        });
    }

    //Totals for tables

    function secondsToTime(secs)
    {
        var hours = Math.floor(secs / (60 * 60));

        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);

        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);
        
        var tmp="";
        if (hours>0)
            tmp += hours + "h ";
        if (minutes>0)
            tmp += minutes + "m ";
        if (seconds>0)
            tmp += seconds + "s";

        return tmp
    }

    function getSeconds(val)
    {
        //val can contain hours + minutes and seconds
        if (val.length >= 4) // has several values
        {
            var values = val.split(" "); //several values
            var secs = 0;
            for (var i = 0;i<values.length;i++)
            {                
                var tmp = values[i];
                
                if (tmp.indexOf('s') >= 0)
                    secs += parseInt(tmp.replace("s",""));
                else if (tmp.indexOf('m') >= 0)
                    secs +=  parseInt(tmp.replace("m",""))*60
                else if (tmp.indexOf('h') >= 0)
                    secs +=  parseInt(tmp.replace("h",""))*60*60
           
            }

            return secs;
        }
        else //has single value or is empty
        {
           
            if (val.indexOf('s'))
            {
                return parseInt(val.replace("s",""));
            }
            else if (val.indexOf('m'))
            {
                return parseInt(val.replace("m",""))*60
            }
            else if (val.indexOf('h'))
            {
                return parseInt(val.replace("h",""))*60*60
            }
        }


    }
    
    function calculateTotals() {
        var tm = $("table")[0]
        var ta = $("table")[1]
        var totals = {}
        var values = {}
    
        values.score = 0;
        values.kills = 0;
        values.deaths = 0;
        values.assists = 0;
        values.pdmg = 0;
        values.sdmg = 0;
        values.accuracy = 0;
        values.count = 0;
        values.played = 0;

        totals.marine = {}
        totals.alien = {}

        totals.marine = jQuery.extend({}, values);
        totals.alien = jQuery.extend({}, values);
    
        //0 = flag
        //1 = image + name
        //2 = Score
        //3,4,5 = K,D,A
        //6 = Player damage
        //7 = Structure damage
        //8 = Accuracy
        //9 = Played

        var tval = 0;
        $(tm).find("tr").each(
        function()
        {
            if (tval > 0) //skip table header
            {
                if (!isNaN(parseInt($($(this).find("td")[2]).html())))
                    totals.marine.score += parseInt($($(this).find("td")[2]).html())
                if (!isNaN(parseInt($($(this).find("td")[3]).html())))
                    totals.marine.kills += parseInt($($(this).find("td")[3]).html())
                if (!isNaN(parseInt($($(this).find("td")[4]).html())))
                    totals.marine.deaths += parseInt($($(this).find("td")[4]).html())
                if (!isNaN(parseInt($($(this).find("td")[5]).html())))
                    totals.marine.assists += parseInt($($(this).find("td")[5]).html())
                if (!isNaN(parseInt($($(this).find("td")[6]).html())))
                    totals.marine.pdmg += parseInt($($(this).find("td")[6]).html())
                if (!isNaN(parseInt($($(this).find("td")[7]).html())))
                    totals.marine.sdmg += parseInt($($(this).find("td")[7]).html())
                if (!isNaN(parseInt($($(this).find("td")[8]).html())))
                    totals.marine.accuracy += parseInt($($(this).find("td")[8]).html())
                if (!isNaN(parseInt($($(this).find("td")[9]).html())))
                    totals.marine.played += getSeconds($($(this).find("td")[9]).html())

                totals.marine.count += 1

            }

            tval++;
        }
    )

        tval = 0;
    
        $(ta).find("tr").each(
        function() 
        {
            if (tval > 0) //skip table header
            {
                if (!isNaN(parseInt($($(this).find("td")[2]).html())))
                    totals.alien.score += parseInt($($(this).find("td")[2]).html())
                if (!isNaN(parseInt($($(this).find("td")[3]).html())))
                    totals.alien.kills += parseInt($($(this).find("td")[3]).html())
                if (!isNaN(parseInt($($(this).find("td")[4]).html())))
                    totals.alien.deaths += parseInt($($(this).find("td")[4]).html())
                if (!isNaN(parseInt($($(this).find("td")[5]).html())))
                    totals.alien.assists += parseInt($($(this).find("td")[5]).html())
                if (!isNaN(parseInt($($(this).find("td")[6]).html())))
                    totals.alien.pdmg += parseInt($($(this).find("td")[6]).html())
                if (!isNaN(parseInt($($(this).find("td")[7]).html())))
                    totals.alien.sdmg += parseInt($($(this).find("td")[7]).html())
                if (!isNaN(parseInt($($(this).find("td")[8]).html())))
                    totals.alien.accuracy += parseInt($($(this).find("td")[8]).html())
                if (!isNaN(parseInt($($(this).find("td")[9]).html())))
                    totals.alien.played += getSeconds($($(this).find("td")[9]).html())

                totals.alien.count += 1
            }
            
            tval++;
        }
    )

        var tmp = "";
        tmp +="<tr class='totals'>";
        tmp +="<td colspan='2'>Totals</td>";
        tmp +="<td>" + totals.marine.score + "</td>";
        tmp +="<td>" + totals.marine.kills + "</td>";
        tmp +="<td>" + totals.marine.deaths + "</td>";
        tmp +="<td>" + totals.marine.assists + "</td>";
        tmp +="<td>" + totals.marine.pdmg + "</td>";
        tmp +="<td>" + totals.marine.sdmg + "</td>";
        tmp +="<td>~ " + parseInt(totals.marine.accuracy/totals.marine.count) + "</td>";
        tmp +="<td>" + secondsToTime(totals.marine.played) + "</td>";
        tmp +="</tr>";
        $(tm).prepend(
        tmp
    );

        tmp = "";
        tmp +="<tr class='totals'>";
        tmp +="<td colspan='2'>Totals</td>";
        tmp +="<td>" + totals.alien.score + "</td>";
        tmp +="<td>" + totals.alien.kills + "</td>";
        tmp +="<td>" + totals.alien.deaths + "</td>";
        tmp +="<td>" + totals.alien.assists + "</td>";
        tmp +="<td>" + totals.alien.pdmg + "</td>";
        tmp +="<td>" + totals.alien.sdmg + "</td>";
        tmp +="<td>~ " + parseInt(totals.alien.accuracy/totals.alien.count) + "</td>";
        tmp +="<td>" + secondsToTime(totals.alien.played) + "</td>";
        tmp +="</tr>";
        $(ta).prepend(
        tmp
    );
    }
    //]]>


    //minimap
    
    //    var minimap = new Minimap_object($('#minimap')[0],'<?php //echo Yii::app()->baseUrl . "/images/minimaps/" . $mapname          ?>',null);
    //    var t = setInterval("minimap.draw()",1000)
</script>