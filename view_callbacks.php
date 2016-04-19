<!--Вывод таблицы в админке-->
<div class="wrap">
    <h3 style="text-align: center;">Управление звонками</h3>

    <div id="center-panel" style="width: 95%; margin: 3px auto 3px auto;">
        <table class="ant-table">

            <thead>
            <tr class="table-header">
                <th>ID</th>
                <th>Имя клиента</th>
                <th>Телефон</th>
                <th>Дата и время заявки</th>
                <th>Статус обработки звонка</th>
                <th>Действия</th>
            </tr>
            </thead>

            <tbody>
            <?php if (count($this->data['callbacks']) > 0): ?>
                <?php foreach ($this->data['callbacks'] as $key => $record): ?>
                    <tr>
                        <td class="id">
                            <div><strong><?php echo $record['ID'] ?></strong></div>
                        </td>
                        <td class="name">
                            <div><strong><?php echo $record['callback_user_name'] ?></strong></div>
                        </td>
                        <td class="phone">
                            <div><strong><?php echo $record['callback_user_phone'] ?></strong></div>
                        </td>
                        <td class="date"><?php echo $record['callback_date'] ?></td>

                        <td class="status">
                            <div id="status"><strong><?php

                                    if ($record['callback_order_status'] == 0) {
                                        echo "<span class=\"wait-ob\"> Звонок не обработан</span>";
                                    } else {
                                        echo "<span class=\"done-ob\"> Звонок обработан</span>";
                                    }
                                    ?></strong>

                            </div>
                        </td>


                        <td class="actions">
                            <div><a class="delete-call" onclick="return deleteService(); "
                                    href="admin.php?page=edit-callbacks&action=delete&id=<?php echo $record['ID']; ?>">Удалить</a>
                            </div>
                            <div><a class="ob-call"
                                    href="admin.php?page=edit-callbacks&action=order_status&id=<?php echo $record['ID']; ?>">Обработан</a>
                            </div>
                            <div><a class="ob-esc"
                                    href="admin.php?page=edit-callbacks&action=order_back&id=<?php echo $record['ID']; ?>">Отменить</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center">Звонков нет</td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
    </div> <!-- /#center-panel -->
</div>




