<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="wide">
    <h1 style="font-size:24px;">Donations for NS2Stats</h1>
    <p>
        NS2Stats is accepting donations. Donations will help keep ns2stats.com running on more powerful server with all features.<br />
        Server cost is 52 &euro; montly. If we can't pay monthly fee then we will move to less powerful server and remove cpu/memory intensive features like live scoreboard and
        round page maps. If you have hidden your stats, donation wont be shown here. Also if you donate without login, your donation will be anonymous.
    </p>

    If you donate any amount you will:
    <ul>
        <li>Help to keep ns2stats.com running on powerful server</li>
        <li>Not see adds anymore, ever</li>
        <li>Motivate us to keep making ns2stats better :)</li>
    </ul>
    If you donate at least 5 euros you will:
    <ul>        
        <li>Get donator status in profile for few months (at least 2)</li>        
        <li>You might get special treatment on ns2stats enabled servers :) (Mods can see you are donator)</li>
    </ul>
    <?php
    if (isset(Yii::app()->user->id))
    {
        $player = Player::model()->findByPk(Yii::app()->user->id);
    }
    else
        echo '<p style="color:orange">Please login if you want to get your donator bonuses. Otherwise your donation will be anonymous.</p>'
        ?>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="25C5NTCPXSBNE">
        <?php
        if (isset($player))
            echo '<input type="hidden" name="custom" value="' . $player->steam_id . '">';
        else
            echo '<input type="hidden" name="custom" value="not_available">';
        ?>    
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
</div>
<div class="wide">
    <br />
    <hr />
    <div id="history">
        <h3>Latest donations</h3>
        <ul>
            <?php
            $criteria = new CDbCriteria;
            $criteria->order = 'added desc';
            $criteria->condition = 'payment_status LIKE "Completed"';
            $criteria->limit = 10;
            $donations = Donation::model()->findAll($criteria);
            foreach ($donations as $donation)
            {
                if (isset($donation->custom) && strlen($donation->custom) > 3)
                    $player = Player::model()->findByAttributes(array('steam_id' => $donation['custom']));
                else
                    $player = null;
                if (isset($player) && $player->hidden == 0)
                {
                    ?>
                    <li>
                        <?php echo $donation->added ?>: <a href="<?php echo Yii::app()->baseUrl . '/player/player/' . $player->id ?>"><?php echo $player->steam_name ?></a> donated <?php echo $donation->mc_gross ?> euros.
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>