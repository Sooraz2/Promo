
        <table id="BoardCastAllListByDate" class="table table-bordered table-sorted">
            <thead>
            <tr>
                <th><a class="table-header" field-name="promotion"><?php echo  $Language->translate("Promotion Method") ?></a></th>
                <th><a class="table-header" field-name="text"><?php echo  $Language->translate("Text") ?></a></th>
                <th><a class="table-header" field-name="quantity"><?php echo  $Language->translate("Quantity of") ?></a></th>

                <th class="Action col-xs-1"><?php echo  $Language->translate("Action") ?></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="loading-msg-countries" class="loading-image"></div>


    <script>
        $(function () {



            $('#BoardCastAllListByDate').ajaxGrid({
                pageSize: 100,
                defaultSortExpression: 'dateadded',
                defaultSortOrder: 'asc',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>BroadcastingCalendar/GetAll?Date='+'<?php echo $Date ?>',
                requestType: 'get',
                loadingImage: $('#loading-msg-countries'),
                postContent: [
                    {
                        control: $('<button name ="Edit Option" type="button" title ="<?php echo  $Language->translate("Edit Service") ?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,"<?php echo  $Language->translate("Edit Option") ?>","<?php echo  BASE_URL?>BroadcastingCalendar/Form",500,200)\'>' +
                        '<small class="glyphicon glyphicon-pencil"></small>' +
//                        ' Edit' +
                        '</button>')
                    },
                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>BroadcastingCalendar/Delete' method='POST'>" +
                        "<input type='hidden' name='ID' id='ID' /> " +
                        '<button name="Delete Broadcasting Promotion" type="submit" title ="<?php echo  $Language->translate("Delete Service") ?>" class="btn btn-rounded btn-danger" onclick=\'return Confirmation(this,"<?php echo  $Language->translate("Delete Option") ?>","<?php echo  $Language->translate("Are you sure you want to delete?") ?>", "<?php echo  $Language->translate("Yes")?>","<?php echo  $Language->translate("No")?>")\'>' +
                        '<small class="glyphicon glyphicon-trash"></small>' +
//                        ' Delete' +
                        '</button></form>'),
                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'id'
                            }
                        ],
                        //removeWhen: {property: 'UserType', value: ['Admin']}
                    }


                ],
                id: 'id',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });
        });

    </script>
