var DataTable = function () {
};

DataTable.prototype =
{
    instance: null,

    initTable: function () {
        var self = this;
        var $rows = this.getDatatable().find('tbody tr');

        $rows.find('td:not(.edit)').click(function () {
            $(this).parent().toggleClass('selected');
        });

        $rows.find('td.edit').click(function () {
            var id = $(this).find('input[name=id]').val();
            self.actionEdit(id);
        });

        $rows.dblclick(function () {
            var id = $(this).find('input[name=id]').val();
            self.actionEdit(id);
        });
    },

    initWindow: function () {
        var self = this;
        var $window = this.getWindow();

        $window.find('.closeButton').click(function () {
            self.closeWindow();
        });

        $window.find('.saveAndClose').click(function () {
            self.actionSave(true);
        });

        $window.find('.save').click(function () {
            self.actionSave(false);
        });
    },

    actionEdit: function (id) {
        var self = this;
        var $window = this.getWindow();

        $('body').addClass('datatableBlur');
        $window.fadeIn();

        $.ajax({
            url: '/cms/datatable/edit',
            type: 'post',
            data: {
                dataTableInstance: self.instance,
                dataTableId: id
            },
            success: function (result) {
                $window.find('.windowContent').html(result);
                self.initWindow();
            },
            error: function (result) {
            }
        });
    },

    actionSave: function (closeWindow) {
        var self = this;
        var $window = this.getWindow();
        var $form = $window.find('form');
        var formContents = $form.serialize();

        $.ajax({
            url: '/cms/datatable/save',
            type: 'post',
            data: formContents,
            success: function (result, responseText, response) {
                if (closeWindow && response.status == 200) {
                    self.closeWindow();
                } else {
                    $window.find('.windowContent').html(result);
                    self.initWindow();
                }
            },
            error: function (result) {
            }
        });
    },

    closeWindow: function()
    {
        $('body').removeClass('datatableBlur');
        this.getWindow().fadeOut();
        this.getWindow().find('.windowContent').html('');
    },

    getDatatable: function () {
        return $("#" + this.instance);
    },

    getWindow: function () {
        var windowId = this.instance + 'Window';

        if ($('body > #' + windowId).length < 1) {
            var $window = '<div class="datatableWindow" id="' + windowId + '">' +
                '<div class="closeButton"></div><div class="windowContent"></div></div>';

            $('body').prepend($window);
        }

        return $('#' + windowId);
    }
};