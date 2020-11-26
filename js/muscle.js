$(function(){
    var entry_url = $("#entry_url").val();

    $("#address_search").click(function() {

        var zip = $("#zip").val();

        if (zip.match(/[0-9]{7}/) === null) {

            alert('正確な郵便番号を入力してください。');
            return false; // ページ遷移しない
        } else {
            $.ajax({
                type : "get",
                url : entry_url + "/postcode_search.php?zip=" + escape(zip),
            }).then(
                function(data){
                    if (data == "no" || data == "") {
                        alert('該当する郵便番号がありません');
                    } else {
                        $("#address").val(data);
                    }
                },
                function(data) {
                    alert("読み込みに失敗しました。");
                },
            );
        }
    });

    var $good = $('.good');

    $good.click(function() {

        var $this = $(this);

        var user_id = $this.children('.user_id').val();
        var review_id = $this.children('.review_id').val();

        $.ajax({
            type : "POST",
            url : entry_url + "/good.php",
            data: { user_id: escape(user_id),
                    review_id: escape(review_id) }
        }).then(
            function(data){
                // いいねの総数を表示
                $this.children('span').html(data);
                // いいね取り消しのスタイル
                $this.children('i').toggleClass('far'); //空洞ハート
                // いいね押した時のスタイル
                $this.children('i').toggleClass('fas'); //塗りつぶしハート
                $this.children('i').toggleClass('active');
                $this.toggleClass('active');
            },
            function(data) {
                alert('読み込みに失敗しました。');
            },
        );
    });

    var $num_change = $('.num_change');

    $num_change.click(function() {

        var $this = $(this);

        var $parent = $this.parent();
        var changed_num = $parent.prev().val();

        var item_id = $this.children('.item_id').val();
        var user_id = $this.children('.user_id').val();

        $.post({
            type : "POST",
            url : entry_url + "/numChangeInCart.php",
            data: { changed_num: escape(changed_num),
                item_id: escape(item_id),
                user_id: escape(user_id) },
        }).then(
            function(data) {
                $('#total').html(data);
            },
            function(data) {
                alert('読み込みに失敗しました。');
            },
        );
    });
});