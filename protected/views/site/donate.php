<?php
$criteria = new CDbCriteria;
$criteria->order = 'added desc';
$criteria->condition = 'payment_status LIKE "Completed" AND added > "2015-01-01"';
$criteria->limit = 1000;
$donations = Donation::model()->findAll($criteria);
$totalEuros = 0;
foreach ($donations as $donation)
    $totalEuros += ($donation->mc_gross - $donation->mc_fee);
?>

<div class="wide">
    <h1 style="font-size:24px;">Donations for NS2Stats</h1>
    <p>
        NS2Stats has been running on good server for about half year now. And its been running without bigger issues for last few months. <br />
        And I would like to keep it that way. So I ask you to help with the server costs. <br /><br />
        NS2Stats is accepting donations to fund yearly server cost (39.60 &euro; * 11 months). Server is i7-2600, 16 GB RAM and 3 TB HDD in raid. Rented from http://www.hetzner.de/.<br /><br />
        Currently funding stands at: <b><?php echo round($totalEuros, 2) ?>&euro; of 435.60 &euro;.</b><br /><br />

        Thank you for your donations!
        If you have hidden your stats, donation wont be shown here. Also if you donate without login, your donation will be anonymous. All donations will be used for ns2stats. If you run into issues contact synomi66@gmail.com.<br />
        You can also directly send any donation to synomi66@gmail.com via paypal, if you want donator status, then please include your ns2id. <br />
        Paypal takes a bit of a cut from donations which for example for 5 euros is 57 cents.
    </p>

    If you donate any amount you will:
    <ul>            
        <li>Help to keep the server running.</li>
        <li>Motivate us to keep making NS2Stats better.</li>
        <li>Get donator status in profile.</li>                
    </ul>

    <p>Thanks<br />
        - Synomi (aka Sint)</p>
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
        <h3>Latest transactions</h3>
        <ul>
            <?php
            foreach ($donations as $donation)
            {
                if (isset($donation->custom) && strlen($donation->custom) > 3 && $donation->custom != 'server_rent' && $donation->custom != 'not_available')
                    $player = Player::model()->findByAttributes(array('steam_id' => $donation['custom']));
                else
                    $player = null;
                if (isset($player) && $player->hidden == 0)
                {
                    ?>
                    <li>
                        <span style="color:grey">[<?php echo $donation->added ?>]</span> <?php echo $donation->added ?>: <a href="<?php echo Yii::app()->baseUrl . '/player/player/' . $player->id ?>"><?php echo $player->steam_name ?></a> donated <?php echo $donation->mc_gross ?> euros.
                    </li>
        <?php
    }
    else if ($donation->custom == 'server_rent')
    {
        ?>
                    <li>
                    <?php echo $donation->added ?>: Server rent paid, balance <?php echo $donation->mc_gross ?> euros.
                    </li>
                        <?php
                    }
                    else if ($donation->custom == 'Anonymous' || $donation->custom == 'not_available')
                    {
                        ?>
                    <li>
                        <span style="color:grey">[<?php echo $donation->added ?>]</span> Anonymous donated <?php echo $donation->mc_gross ?> euros.
                    </li>
        <?php
    }
}
?>
        </ul>
    </div>
</div>