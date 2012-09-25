// @IT連作「Ajaxおいしいレシピ」用の名前空間オブジェクト
var ajaxrecipe = new Object();

/* コンストラクタ */
ajaxrecipe.Filer = function() {
    $(window).bind("load", {filer: this}, this.onLoadWindow);
}

ajaxrecipe.Filer.prototype = {
    /**
     * ページ読み込み完了のイベントハンドラ。
     * ディレクトリの初期化、ファイルの初期化を行う。
     */
    onLoadWindow: function(event) {
	/* ディレクトリの初期化
         * 1. 子要素(=ファイル)を選択可に設定
         * 2. ドロップ可に設定
         */
        $('.directory').each(function() {
            $(this).selectable({
                selected:   event.data.filer.onSelected,
                unselected: event.data.filer.onUnselected
            });
            $(this).droppable({
                hoverClass: 'ui-droppable-hover',
                accept:     event.data.filer.isAccept,
                drop:       event.data.filer.onDrop
            });
        });

        /* ファイルの初期化
         * 1. ドラッグ可能に設定
         * 2. ドラッグの一時無効化
         */
        $('.file').draggable({
            containment: '#filer',
            start:       event.data.filer.onStartDrag,
            drag:        event.data.filer.onDrag,
            stop:        event.data.filer.onStopDrag
        }).draggable('disable');
    },
    /**
     * ファイルがドロップ可能かどうかを判定する関数。
     * ドラッグの際、乗っているディレクトリが
     * 元と同じディレクトリでなければ、ドロップ可能。
     */
    isAccept: function(draggable) {
	var oldDirectory = $(draggable).parent().get(0);
	var newDirectory = this.get(0);
        if (newDirectory.id != oldDirectory.id) {
            return true;
        }
        else {
            return false;
        }
    },
    /* ファイル選択のイベントハンドラ。
     * 選択がされたファイルのドラッグを一時有効化。 */
    onSelected: function(e, ui) {
        var file = $(ui.selected);
        file.draggable('enable');
    },
    /* ファイル選択解除のイベントハンドラ。
     * 選択解除されたファイルのドラッグを一時無効化 */
    onUnselected: function(e, ui) {
        var file = $(ui.unselected);
        file.draggable('disable');
    },
    /**
     * ドラッグ開始のイベントハンドラ。
     * 複数ファイルのドラッグを行うために、
     * 選択されたファイルの初期座標を保持。
     */
    onStartDrag: function(e, ui) {
        var helper   = ui.helper.get(0);
        var parentId = $(helper).parent().get(0).id;
        helper.selectedFiles = $('#' + parentId + ' .ui-selected');
        helper.selectedFiles.each(function() {
            this.startTop  = parseInt($(this).css('top'));
            this.startLeft = parseInt($(this).css('left'));
        });
    },
    /**
     * ドラッグ中のイベントハンドラ。
     * ドラッグ中少しでも位置が変わると呼び出される。
     * 次の処理を行う。
     *   1. ドラッグされたファイルの初期座標からの
     *      移動距離を測る。
     *   2. ドラッグされたファイル以外の、
     *      選択されたすべてのファイルを
     *      初期座標と移動距離を足した位置へ移動する。
     */
    onDrag: function(e, ui) {
        var helper       = ui.helper.get(0);
        var topDistance  = ui.position.top  - helper.startTop;
        var leftDistance = ui.position.left - helper.startLeft;
        helper.selectedFiles.each(function() {
	    if (this.selectedFiles == null) {
		var newTop  = this.startTop  + topDistance;
		var newLeft = this.startLeft + leftDistance;
		$(this).css('top',  newTop  + 'px')
                       .css('left', newLeft + 'px');
            }
	});
    },
    /**
     * ドラッグ終了のイベントハンドラ。
     * 選択ファイルへの参照を消去
     */
    onStopDrag: function(e, ui) {
        var helper          = ui.helper.get(0);
        helper.selectedFiles = null;
    },
    /**
     * ディレクトリへのドロップのイベントハンドラ。
     * 次の処理を行う。
     *   1. ドロップされたファイルを子要素として登録
     *   2. そのファイルの位置を初期化
     *   3. 選択を解除
     *   4. ドラッグの無効化
     */
    onDrop: function(e, ui) {
        var directory = ui.instance.element;
        var files     = $('.ui-selected');
        files.appendTo(directory)
             .css('top',  '0px')
             .css('left', '0px')
             .removeClass('ui-selected')
             .draggable('disable');
    }
}

new ajaxrecipe.Filer();