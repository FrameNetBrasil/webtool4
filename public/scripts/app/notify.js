const manager = {
    // messager(type, message) {
    //     if ((type === 'error') || (type === 'warning')|| (type === 'info')) {
    //         $.notify.alert( '', message, type);
    //     } else {
    //         $.notify.show({
    //             cls: 'wt-messager wt-messager-' + type,
    //             title: null,//type.charAt(0).toUpperCase() + type.slice(1),
    //             label: type.charAt(0).toUpperCase() + type.slice(1),
    //             msg: message,
    //             timeout: 4000,
    //             showType: 'show',
    //             style: {
    //                 right: '',
    //                 top: document.body.scrollTop + document.documentElement.scrollTop,
    //                 bottom: ''
    //             }
    //         });
    //     }
    // },
    confirmPost(type, message, action) {
        $.messager.confirm({
            cls: 'wt-messager wt-messager-' + type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            msg: message,
            fn: function(r){
                if (r){
                    console.log('confirmed: '+r);
                }
            }
        });
    },
    confirmDelete(message, action, onApprove, onDeny) {
        console.log('confirmDelete');
        $.toast({
            title: 'Warning',
            message: message + ' Confirm?',
            displayTime: 0,
            position: 'centered',
            actions:	[{
                text: 'Yes',
                icon: 'check',
                class: 'green',
                click: async () => {
                    $('body').dimmer('hide');
                    await htmx.ajax('DELETE', action, null);
                    if (onApprove) {
                        onApprove();
                    }
                }
            },{
                icon: 'ban',
                class: 'secondary',
                text: 'No',
                click: function() {
                    $('body').dimmer('hide');
                    if (onDeny) {
                        onDeny();
                    }
                }
            }],
            onShow: function() {
                $('body').dimmer('show');
            }
        });

    },
    notify(type, message) {
        $.toast({
            class: type,
            message: message,
            className: {
                content: 'content  wt-notify-' + type,
            },
        });
    }
};

$(function () {
    document.body.addEventListener("notify", function(evt) {
        console.log(evt.detail.type, evt.detail.message);
        $.toast({
            class: evt.detail.type,
            message: evt.detail.message,
            className: {
                content: 'content  wt-notify-' + evt.detail.type,
            },
        })
        ;
    });
});
