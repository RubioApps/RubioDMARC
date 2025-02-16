jQuery.extend({

    tmpl: {
        container: '.od-main',
        header: '.od-header',
        footer: '.od-footer',
        aside: '#channels',
        query: '#query',
        menu: '#mainmenu',
        toaster: '#od-toast',
        overlay: '#od-overlay',
        theme: '#btn-theme-switch',
        language: '#btn-lang-switch'
    },

    od: {
        livesite: '',
        assets: '',
        labels: {},
        cache: {},
        xhr: null,
        logged: false,

        init: function (url, assets) {
            $.od.livesite = url;
            this.assets = assets;

            $.ajaxSetup({ timeout: 10000 });

            //Store labels
            if (!localStorage.getItem('od-labels')) {
                $.getJSON($.od.livesite + '/?task=labels&format=json', (data) => {
                    localStorage.setItem('od-labels', JSON.stringify(data));
                    for (let key in data) {
                        if (data.hasOwnProperty(key)) {
                            $.od.labels[key.toLocaleLowerCase()] = data[key];
                        }
                    }
                });
            } else {
                data = JSON.parse(localStorage.getItem('od-labels'));
                for (let key in data) {
                    if (data.hasOwnProperty(key)) {
                        $.od.labels[key.toLocaleLowerCase()] = data[key];
                    }
                }
            }

            //Keep alive
            setInterval(() => {
                $.getJSON($.od.livesite + '/?task=keepalive&format=json', function (data) {
                    if (data.success) {
                        console.log(data.message);
                    } else {
                        top.document.location.href = $.od.livesite;
                    }
                });
            }, 300 * 1000);

            //Chang theme
            $($.tmpl.theme).on('click', function (e) {
                e.preventDefault();
                const mode = $(this).attr('data-mode');

                if (mode == 'dark') {
                    $(this).addClass('bi-moon-stars btn-primary').removeClass('bi-sun btn-warning');
                    $('html').attr('data-bs-theme', 'light');
                    $('.od-bg-dark').removeClass('od-bg-dark').addClass('od-bg-light');
                    $(this).attr('data-mode', 'light');
                    $.getJSON($.od.livesite + '/?task=theme&mode=light');
                } else {
                    $(this).removeClass('bi-moon-stars btn-primary').addClass('bi-sun btn-warning');
                    $('html').attr('data-bs-theme', 'dark');
                    $('.od-bg-light').removeClass('od-bg-light').addClass('od-bg-dark');
                    $(this).attr('data-mode', 'dark');
                    $.getJSON($.od.livesite + '/?task=theme&mode=dark');
                }

                const menu = bootstrap.Collapse.getOrCreateInstance($('#mainmenu'));
                menu.hide();
            });

            //Chang language
            $($.tmpl.language).on('change', function (e) {
                e.preventDefault();
                const lang = $(this).val();
                $.getJSON($.od.livesite + '/?task=language&hl='+lang+'&format=json', () =>{
                    top.location.reload();
                });                
            });            

            this.lazyimage();
        },

        lazyimage: () => {
            $('img[data-remote]').each(function () {
                const img = $(this);
                const src = img.attr('src');
                const url = img.attr('data-remote');
                if (url && url != src) {
                    $.ajax({
                        url: url,
                        cache: false,
                        type: 'get',
                        dataType: 'json',
                        success: function (data, status, xhr) {
                            const timestamp = new Date().getTime();
                            img.removeClass('spinner-border');
                            img.attr('src', data.logo);
                        },
                        error: function (xhr, status, error) {
                            img.removeClass('spinner-border');
                            img.attr('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=');
                        },
                        complete: function (xhr, status) {
                            img.attr('data-remote', null);
                        }
                    });
                } else {
                    img.removeClass('spinner-border');
                    img.attr('data-remote', null);
                }
            });
        },

        token: function (tokenName = null) {
            if (!tokenName) tokenName = 'token';
            $.get($.od.livesite + '/?task=token&name=' + tokenName).done(function (data) {
                const input = $('input#' + tokenName);
                input.attr('name', data[tokenName]);
                input.val(data.sid);
            });
        },

        login: function (selector) {
            $(selector).on('click', function (e) {
                e.preventDefault();
                const url = $('input#url').val();
                const pwd = $('input#pwd').val();
                const token = $('input#token').attr('name');
                const sid = $('input#token').val();

                data = { 'password': pwd, [token]: sid };
                const posting = $.post(url, data);
                posting.done(function (result) {
                    $.od.toast(result.message, result.error);
                    if (result.error) {
                        $.od.token();
                    } else {
                        setTimeout(1000, top.document.location.href = $.od.livesite);
                    }
                });
            });
        },

        toast: function (text, error = false) {
            const wrapper = $($.tmpl.toaster);
            const toast = wrapper.find('.toast').first().clone();
            toast.addClass('temp');
            toast.removeClass('d-none');
            toast.find('.toast-body').html(text);
            toast.addClass(error ? 'bg-danger' : 'bg-success');
            toast.appendTo(wrapper);
            const tbs = bootstrap.Toast.getOrCreateInstance(toast.get(0));
            tbs.show();
            setTimeout(function () { $('.toast.temp').remove() }, 2000);
        },
    
    }
});