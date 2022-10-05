console.log("cemf-utility.js");
var cemf = cemf || {
    js: {}
};

cemf.js.Utility = function () {
}

cemf.js.Utility.prototype.ajaxResponseBaseTime = 3;
cemf.js.Utility.prototype.ajaxResponseRequestError = "<div class='message error icon-warning'>Desculpe mas não foi possível processar sua requisição...</div>";

cemf.js.Utility.prototype.animateMenuBar = false;

cemf.js.Utility.prototype.sizeTablet = 1024;

cemf.js.Utility.prototype.messageCOVID = "COVID19";

cemf.js.Utility.prototype.url_current = function () {
    var url = window.location.href;
    if (url.match(/localhost/)) {
        return 'https://www.localhost/projetos/CEMF/automacaonaveia';
    }
    return 'https://automacaonaveia.cemf.com.br';
}

cemf.js.Utility.prototype.date_fmt_br = function (_date) {
    var text = "" + _date;
    var $new_date = text.replace(" ", "T");
    var $date = new Date($new_date);
    var result = $date.getDay() + "/" + $date.getMonth() + "/" + $date.getFullYear();
    return (result);
}

cemf.js.Utility.prototype.dateToPT = function (date) {
    return date.split('-').reverse().join('/');
}

cemf.js.Utility.prototype.formatNumberPT = function (number) {
    return number.toLocaleString('pt-br', { minimumFractionDigits: 2 });
}

/* Verifica se a TELA é de TABLET/CELULAR ou não, */
cemf.js.Utility.prototype.isCelular = function () {
    return (this.screenSize() < this.sizeTablet) ? true : false;
}

/* Verifica se existe um seletor*/
cemf.js.Utility.prototype.exists = function (selector) {
    return $(selector).length > 0 ? true : false;
}

/* Captura o Tamanho da tela, retornando a largura*/
cemf.js.Utility.prototype.screenSize = function (selector) {
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth,
        y = w.innerHeight || e.clientHeight || g.clientHeight;
    return x;
}

/*****************************
 *  Animação do CSS via Javascript
 *********************/
cemf.js.Utility.prototype.animateCSS = (element, animation, prefix = 'animate__') =>
    // We create a Promise and return it
    new Promise((resolve, reject) => {
        const animationName = `${prefix}${animation}`;
        const node = document.querySelector(element);
        //console.log(node);

        node.classList.add(`${prefix}animated`, animationName);

        // When the animation ends, we clean the classes and resolve the Promise
        function handleAnimationEnd(event) {
            event.stopPropagation();
            node.classList.remove(`${prefix}animated`, animationName);
            resolve('Animation ended');
        }

        node.addEventListener('animationend', handleAnimationEnd, {
            once: true
        });
        
    });



/*****************************
 *  Função de SCROOL CONFORME DATA-ROLE
 *********************/
cemf.js.Utility.prototype.scrollPage = function (selector) {
    console.log("clicou");
    self = this;
    var role = $(selector).data('role');
    if (self.exists($(role))) {
        pos = $(role).offset().top - 100;
    } else {
        pos = 0;
    }
    $('html, body').animate({
        scrollTop: pos
    }, 400, 'linear');

    $(".j_menu_mobile_close").trigger("click");

}

/*****************************
 *  FUNÇÃO DE OPEN DIV/CLOSE PARA QUESTIONS
 *********************/
cemf.js.Utility.prototype.openQuestions = function (selector) {
    var open = $(selector).data("question-open");
    $(selector).toggleClass('rotate');
    $("[data-" + open + "]").animate({ 'height': 'toggle' }, 300, function () {
    });
}




/*****************************
 *  DISPARO DE MENSAGENS
 *********************/
cemf.js.Utility.prototype.ajaxMessage = function (message, time) {
    var ajaxMessage = $(message);

    ajaxMessage.append("<div class='message_time'></div>");
    ajaxMessage.find(".message_time").animate({ "width": "100%" }, time * 1000, function () {
        //console.log($(this).parents(".message"));
        $(this).parents(".message").addClass('animated slideOutRight').fadeOut(400);
    });

    $(".ajax_response").append(ajaxMessage);
    ajaxMessage.effect("bounce");
}

cemf.js.Utility.prototype.messageStart = function () {
    var self = this;
    $(".ajax_response .message").each(function (e, m) {
        self.ajaxMessage(m, self.ajaxResponseBaseTime + 1);
    });

    $(".ajax_response").on("click", ".message", function (e) {
        $(this).effect("bounce").fadeOut(1);
    });
}

/***************************************
 *  EVENTO POST PADRÃO DOS FORMULÁRIOS
 ***************************************/
cemf.js.Utility.prototype.startPostForm = function ($time) {
    var self = this;
    self.messageStart();
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $("[data-modal-loading]");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                console.log("Before");
                load.fadeIn('fast');
            },
            uploadProgress: function (event, position, total, completed) {
                var loaded = completed;
                var load_title = $("[data-info]");
                load_title.text("Enviando (" + loaded + "%)");

                form.find("input[type='file']").val(null);
                if (completed >= 100) {
                    load_title.text("Aguarde...");
                }
            },
            success: function (response) {
                console.log("Success");
                console.log(response);

                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }

                //reload
                if (response.reload) {
                    location.reload();
                }

                //message
                if (response.message) {
                    self.ajaxMessage(response.message, ($time != null) ? $time : self.ajaxResponseBaseTime);
                }
            },
            complete: function (response) {
                console.log("Complete");

                if (response.reload == null) {
                    load.fadeOut('fast');
                }

                if (form.data("reset") === true) {
                    form.trigger("reset");
                }

                $('html, body').animate({ scrollTop: 0 }, 50);
            },
            error: function (event) {
                load.fadeOut('fast', function () {
                    self.ajaxMessage(self.ajaxResponseRequestError, 5);
                });
            }
        });
    })
}

cemf.js.Utility.prototype.startWeb = function () {
    // mobile menu open
    var self = this;
    self.start();
    self.startPostForm(); // Carregando o evento submit nos formulários

    $(".j_menu_mobile_open").click(function (e) {
        e.preventDefault();
        $(".j_menu_mobile_tab").css("left", "auto").fadeIn(1).animate({ "right": "0" }, 200);
    });

    // mobile menu close
    $(".j_menu_mobile_close").click(function (e) {
        e.preventDefault();
        $(".j_menu_mobile_tab").animate({ "left": "100%" }, 200, function () {
            $(".j_menu_mobile_tab").css({
                "right": "auto",
                "display": "none"
            });
        });
    });
}

cemf.js.Utility.prototype.start = function () {
    var self = this;

    /* ROLAGEM DA TELA PELO MENU */
    $("[data-role]").on('click', function (e) {
        e.preventDefault();
        self.scrollPage($(this));
    });

    /* ABERTURA DAS REPOSTAS */
    $("[data-question]").on("click", function (e) {
        e.preventDefault();
        var id = $(this).data('question');
        if ($("[data-answer =" + id + "]").is(":visible")) {
            $(this).find('a').html("+");
        } else {
            $(this).find('a').html("-");
        }
        $("[data-answer =" + id + "]").animate({ 'height': 'toggle' }, 300, function () {
        });
        //self.openQuestions($(this));
    });

    $("[data-question-open]").on('click', function (e) {
        e.preventDefault();
        console.log("Oi");
        self.openQuestions($(this));
    });

    /***************
     * OPEN MODAL
     ***************/
    $("[data-close]").on('click', function () {
        var close = $(this).data("close");
        $("[data-" + close + "]").fadeOut('fast');
    })

    $("[data-modal-open]").on('click', function (e) {
        e.preventDefault();
        var open = $(this).data("modal-open");
        var close = $(this).data("modal-close");
        var info = $(this).data("modal-info");
        var href = $(this).attr("href");
        console.log("open : " + open);
        if (info != null)
            $("[data-info]").html(info);
        if (close != null)
            $("[data-" + close + "]").fadeOut(30);

        if (href != null && href != "#") {
            window.location.href = href;
        }

        $("[data-" + open + "]").fadeIn("fast");
    })


    /* LIMITE DE CARACTERES */
    //console.log("capturando");
    $("[data-limit]").keyup(function () {
        console.log('limite');
        var limit = $(this).data('limit');
        var id = $(this).attr('id');
        var val = $(this).val();
        if (val.length >= limit) {
            $(this).val(val.substr(0, limit));
            val = $(this).val();
        }

        $("[data-limit-" + id + "]").text(val.length + "/" + limit);
    });


    /*************************
     *  INPUT MAGIC
     *************************/

    $(".input_magic:not('.ajax_off') input").on('keyup', function () {
        var name = $(this).attr('name');
        var label = $("label[for='" + name + "']");
        var input = $(this);
        console.log($(input).val().length);

        if ($(input).val().length >= 1) {
            $(label).addClass("inputMagicUp");
            // $(this).parent().css('margin-top','10px');
        } else {
            $(label).removeClass("inputMagicUp");
            // $(this).parent().css('margin-top','3px');
        }
    })

    /*************************
     *  INPUT TEXTAREA
     *************************/

    $(".input_magic:not('.ajax_off') textarea").on('keyup', function () {
        var name = $(this).attr('name');
        var label = $("label[for='" + name + "']");
        var input = $(this);
        console.log($(input).val().length);

        if ($(input).val().length >= 1) {
            $(label).addClass("inputMagicUp");
            // $(this).parent().css('margin-top','10px');
        } else {
            $(label).removeClass("inputMagicUp");
            // $(this).parent().css('margin-top','3px');
        }
    })

}


