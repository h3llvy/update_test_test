class Block {
    constructor(selector) {
        this.elem = $(`${selector}`);
    }

    getElem() {
        return this.elem;
    }

    changeContent(content) {
        this.elem.html(content)
    }

    getValue() {
        return this.elem.val();
    }

    getHtml() {
        return this.elem[0].outerHTML;
    }
}

class Button {
    constructor(selector) {
        this.elem = $(`${selector}`);
    }

    onClick(func, arg = null) {
        this.elem.click(function () {
            if (arg)
                func(arg)
            else
                func();
        });
    }
}

class ButtonClickReq extends Button {
    constructor(selector) {
        super(selector);
    }

    onClick(req = null) {
        if (req) {
            this.elem.click(function () {
                req.send();
                // if (req.method === 'POST')

            });
        } else this.elem.unbind();

    }
}

class ManagerRequests {
    constructor(method, url, data = null) {
        this.http = new XMLHttpRequest();
        this.data = data;
        this.method = method;
        this.url = url;
    }

    send() {
        this.http.open(this.method, this.url);
        if (this.data) {
            let data = new FormData();
            for (let [key, value] of Object.entries(this.data)) {
                if (typeof value === 'object') {
                    value = value.getValue()
                }
                data.append(key, value)

            }
            if (this.method === "POST") {
                data.append('captcha', grecaptcha.getResponse())
            }
            ;
            this.http.send(data);
        } else {
            this.http.send();
        }

    }
}


var loadCaptcha = function () {
    recaptcha = grecaptcha.render('recaptcha', {
        'sitekey': '6LcXdm8bAAAAAIfagC-DXbNuWUiEstmlfG04eskc'

    });
};

function create(a) {
    setTimeout(function () {
        loadCaptcha()
    }, 300)

}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

let main = new Block('main');
let navButtons = new Block('.__nav_buttons');

let reqFormIn = new ManagerRequests('GET', 'src/source.php')
reqFormIn.http.onload = function () {
    let inBut = new ButtonClickReq('.__signIn');
    let h1But = new ButtonClickReq('h1');

    main.changeContent(JSON.parse(reqFormIn.http.responseText)['main']);
    if (JSON.parse(reqFormIn.http.responseText)['nav']) {
        navButtons.changeContent(JSON.parse(reqFormIn.http.responseText)['nav']);

    }

    if (JSON.parse(reqFormIn.http.responseText)['recaptcha']) {
        // alert(1)
        create()
        // alert(2)
    }

    h1But.onClick(reqFormIn);
    h1But.onClick();
    inBut.onClick(reqFormIn);
    h1But.onClick(reqFormIn);

    let upBut = new ButtonClickReq('.__signUp');
    let reqFormUp = new ManagerRequests('GET', 'src/source.php?action=form_up');
    reqFormUp.http.onload = function () {

        main.changeContent(JSON.parse(reqFormUp.http.responseText)['main']);
        navButtons.changeContent(JSON.parse(reqFormIn.http.responseText)['nav']);
        // alert(JSON.parse(reqFormUp.http.responseText)['recaptcha']
        if (JSON.parse(reqFormUp.http.responseText)['recaptcha']) {
            create()
        }

        inBut = new ButtonClickReq('.__signIn');
        inBut.onClick(reqFormIn);

        let inputEmail = new Block('.__email');
        let inputPass = new Block('.__password');
        let inputVerifPass = new Block(('.__pass_verif'))

        let signUp = new ButtonClickReq('.__reg',);
        let reqUp = new ManagerRequests('POST', 'src/source.php',
            {'action': 'up', 'email': inputEmail, 'pass': inputPass});
        reqUp.http.onload = function () {
            if (!JSON.parse(reqUp.http.responseText)['alert']) {
                reqFormIn.send();
                return;
            } else {
                alert(JSON.parse(reqUp.http.responseText)['alert']);
            }
        }

        signUp.elem.click(function () {
            if (!validateEmail($('.__email').val())) {
                alert("Неверный формат почты")
                return;
            }
            if (!(inputPass.getValue().length >= 8 && inputPass.getValue().length <= 40)) {
                alert("Длина пароля не менее 8 и не более 40 символов");
                return;
            }
            if (inputPass.getValue() !== inputVerifPass.getValue()) {
                alert("Пароли не сходятся");
                return;
            }
            reqUp.send()
        });

    }
    upBut.onClick(reqFormUp);

    let reqOut = new ManagerRequests('GET', 'src/source.php?action=out')
    let signOutBut = new ButtonClickReq('.__signOut');
    reqOut.http.onload = function () {
        reqFormIn.send();
    }
    signOutBut.onClick(reqOut)

    let reqFormSettings = new ManagerRequests('GET', 'src/source.php?action=form_settings')
    let butFormSettings = new ButtonClickReq('.__settings');
    reqFormSettings.http.onload = function () {
        main.changeContent(JSON.parse(reqFormSettings.http.responseText)['main'])
        if (JSON.parse(reqFormSettings.http.responseText)['recaptcha']) {
            create()
        }

        let reqEmailNotActived = new ManagerRequests('GET', 'src/source.php?action=form_activity')
        let butEmailNotActived = new ButtonClickReq('.__active_email')
        if (butEmailNotActived.elem[0].classList[butEmailNotActived.elem[0].classList.length - 1] != 'blue')
            reqEmailNotActived.http.onload = function () {
                if (!JSON.parse(reqEmailNotActived.http.responseText)['main']) {
                    alert("Вы не авторизованы");
                } else {
                    main.changeContent(JSON.parse(reqEmailNotActived.http.responseText)['main']);
                }
            };

        butEmailNotActived.elem.click(function () {
            this.classList.add('blue')
            let list = document.querySelector(".__labels");
            let item = document.createElement("label")
            let item2 = document.createElement("input")
            let item3 = document.createElement("button")
            let item4 = document.createElement("button")
            item.innerHTML = 'Код из письма'
            item.classList.add('hide',)
            item3.innerHTML = 'Активировать';
            item3.classList.add('__active', 'hide')
            item2.classList.add('__code', 'hide')
            item4.innerHTML = 'Выслать код'
            item4.classList.add('__send_code', 'hide')
            list.insertBefore(item, list.childNodes[list.childNodes.length - 3]);
            list.insertBefore(item2, list.childNodes[list.childNodes.length - 3]);
            list.insertBefore(item3, list.childNodes[list.childNodes.length - 3]);
            list.insertBefore(item4, list.childNodes[list.childNodes.length - 3]);
            $('#recaptcha')[0].classList.add('hide')
            for (let item of document.querySelector(".__labels").children) {
                item.classList.toggle('hide')
            }

            $('.__active_email').unbind();

            let reqSendNewCode = new ManagerRequests('GET', 'src/source.php?action=send_code')
            reqSendNewCode.http.onload = function () {
                alert('Код выслан');
            }
            let butSendNewCode = new ButtonClickReq('.__send_code')
            butSendNewCode.onClick(reqSendNewCode)

            let inputCode = new Block('.__code')
            let reqActive = new ManagerRequests('POST', 'src/source.php', {
                'action': 'active_code',
                'code': inputCode
            })

            reqActive.http.onload = function () {
                if (JSON.parse(reqActive.http.responseText)['alert']) {
                    alert(JSON.parse(reqActive.http.responseText)['alert'])
                } else {
                    reqFormSettings.send();
                }
            }

            let butActive = new ButtonClickReq('.__active')
            butActive.onClick(reqActive)

        })

        let inputName = new Block('.__name');
        let inputImgUrl = new Block('.__img');
        let inputOldPass = new Block('.__oldPassword');
        let inputCurPass = new Block('.__curPassword');
        let inputVerifPass = new Block('.__verification');

        let reqSaveSettings = new ManagerRequests('POST', 'src/source.php',
            {
                'action': 'save_settings',
                'name': inputName,
                'photo': inputImgUrl,
                'old_pass': inputOldPass,
                'curr_pass': inputCurPass,
            });

        reqSaveSettings.http.onload = function () {
            if (inputVerifPass.getValue() != inputCurPass.getValue()) {
                alert("Пароли не сходятся")
                return;
            }

            if (!(inputOldPass.getValue().length == 0 || (inputCurPass.getValue().length >= 8 && inputCurPass.getValue().length <= 40))) {
                alert("Длина пароля не менее 8 и не более 40 символов")
                return;
            }

            if (reqSaveSettings.http.responseText) {
                alert(JSON.parse(reqSaveSettings.http.responseText)['alert']);
            } else alert('Ошибка с поключением');
        };

        let butSaveSettings = new ButtonClickReq('.__saveSettings');
        butSaveSettings.onClick(reqSaveSettings);

    }
    butFormSettings.onClick(reqFormSettings)


    let reqViewAllUsers = new ManagerRequests('GET', 'src/source.php?action=view_all_users')
    reqViewAllUsers.http.onload = function () {
        reqViewAllUsers.url = "src/source.php?action=view_all_users"

        if (!reqViewAllUsers.http.responseText) {
            alert("У вас нет прав, войдите в аккаунт и активируйте почту");
        } else {
            main.changeContent(JSON.parse(reqViewAllUsers.http.responseText)['main']);

            let butTr = new ButtonClickReq('.row_user');


            butTr.elem.click(function () {
                    let trKey = $('.__key', this)[0].innerHTML;
                    let reqViewUser = new ManagerRequests('GET', 'src/source.php?action=view_user&key=' + trKey);
                    reqViewUser.http.onload = function () {
                        if (JSON.parse(reqViewUser.http.responseText)['main']) {
                            main.changeContent(JSON.parse(reqViewUser.http.responseText)['main']);
                        } else alert("Вы не авторизованы")
                    }
                    reqViewUser.send();
                }
            )

            for (th of document.querySelector('.ui-widget-header ').children) {
                console.log(th.id)
                reqViewAllUsersOrders = {}
                th.onclick = function () {
                    reqViewAllUsers.url = "src/source.php?action=view_all_users&sort=" + this.id;
                    reqViewAllUsers.send()
                }
            }


        }
    }
    let butViewUsers = new ButtonClickReq('.__users');
    butViewUsers.onClick(reqViewAllUsers);


    let email = new Block('.__email');
    let pass = new Block('.__password');

    let inToServerBut = new ButtonClickReq('.__signInToServer');
    let reqInToServer = new ManagerRequests('POST', 'src/source.php',
        {'action': 'in', 'email': email, 'pass': pass});
    reqInToServer.http.onload = function () {
        if (!reqInToServer.http.responseText) {
            reqFormIn.send()
        } else if (JSON.parse(reqInToServer.http.responseText)['alert']) {
            alert(JSON.parse(reqInToServer.http.responseText)['alert'])
        }
    }
    inToServerBut.onClick(reqInToServer);
}
reqFormIn.send()