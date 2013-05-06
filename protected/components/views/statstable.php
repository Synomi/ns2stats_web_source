<div class="grid-view">
    <table class="items">
        <thead>
            <tr>
                <?php
                foreach($this->columns as $column) {
                    echo CHtml::tag('th', array(), $column['title']);
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $nextClass="odd";
            $i = 1;
            foreach($this->rows as $data) {
                echo '<tr class="' . $nextClass . '">';
                foreach($this->columns as $column) {
                     echo '<td>';
                    eval('echo ' . $column['value'] . ';');
                    echo '</td>';
                }
                echo '</tr>';
                $nextClass = ($nextClass=="odd")? "even" : "odd";
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
