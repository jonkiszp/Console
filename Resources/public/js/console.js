function Console(element, db) {
    // inicjalizacja bay danych
    db.version(1).stores({
        complete: "++id, name, value, score, meta"
    });

    this.lock = false;

    //Inicjalizacja edytora
    this.langTools = ace.require('ace/ext/language_tools');
    this.editor = ace.edit(element);

    // ustawienie edytora do podpowiadania składni
    this.editor.focus();
    this.editor.$blockScrolling = Infinity;
    this.editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true,
        fixedWidthGutter: true
    });

    // ustawienie stylu
    this.editor.setTheme('ace/theme/twilight');
    this.editor.session.setMode('ace/mode/php');

    // odczytanie schowka i ustawienie kursora w odpowiedniej lini
    if (localStorage.getItem('source') !== null) {
        this.editor.setValue(JSON.parse(localStorage.getItem('source')));
    }
    var count = this.editor.session.getLength();
    this.editor.gotoLine(count, this.editor.session.getLine(count-1).length);

    // podpięcie zdarzenia zmian do zapisu danych edytora
    this.editor.getSession().on('change', function() {
        localStorage && localStorage.setItem('source', JSON.stringify(self.getValue()));
    });

    var self = this.editor;

    // inicjalizacja mechanizmu podpowiedzi składni
    var dynamicCompleterPhp = {
        getCompletions: function (editor, session, pos, prefix, callback) {

            if (prefix.length === 0 && self.lock) {
                callback(null, []); return
            }

            //{name: item.name, value: item.value, score: item.score, meta: item.meta}
            db.complete.toArray().then(function (items) {
                callback(null, items.map(function (item) {
                    return item;
                }));
            });
        }
    };
    this.langTools.addCompleter(dynamicCompleterPhp);

    // funkcja wyszukująca
    this.runCode = function (responseElement) {
        var ajax = $.post(window.location.href + '/php', {codeRun: JSON.stringify({
            code: self.getValue(),
        })});

        // jeśli wszystko poszło ok
        ajax.done(function (codeResponse) {
            $('#response').attr('srcdoc', codeResponse);
        });

        // jeśli coś poszło nie tak
        ajax.fail(function (data) {
            $('#response').attr('srcdoc', data.responseText);
        });
    };

    // funkcja ładująca podpowiedzi
    this.loadHint = function () {
        $('#loader').show();
        $.ajax({
            url: window.location.href + '/completer',
            dataType: 'json',
            cache: false,
            throws: true
        }).done(function (items) {
            self.lock = true;
            db.transaction('rw', db.complete, function () {
                db.complete.clear();
                db.complete.bulkAdd(items);
            }).then(function () {
                self.lock = false;
            }).catch(function (ex) {
                console.error(ex);
                self.lock = false;
            });
        }).fail(function () {
            alert('Błąd synchronizacji danych');
        }).always(function () {
            $('#loader').hide();
        });
    };

    // funkcja ustawiająca skróty klawiszowe
    this.setShortcut = function () {
        // uruchamia napisany kod
        self.commands.addCommands([{
            name: 'executeCode',
            bindKey: {win: 'Ctrl-Enter', mac: 'Command-Enter'},
            exec: this.runCode,
            readOnly: true
        },{
            name: 'loadHint',
            bindKey: {win: 'Alt-R', mac: 'Command-R'},
            exec: this.loadHint,
            readOnly: true
        },{
            name: 'showKeyboardShortcuts',
            bindKey: {win: 'Ctrl-Alt-h', mac: 'Command-Alt-h'},
            exec: function (editor) {
                ace.config.loadModule('ace/ext/keybinding_menu', function (module) {
                    module.init(editor);
                    editor.showKeyboardShortcuts();
                });
            },
            readOnly: true
        }]);
    };
}

// Utworzenie bazy danych
var db = new Dexie('CompleterDictionary');
// Wywołanie programu
var app = new Console('console', db);
app.setShortcut();