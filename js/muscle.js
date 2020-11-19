$(function(){
    var entry_url = $("#entry_url").val();

    $("#cart_in").click(function(){
        var item_id = $("#item_id").val();
        var item_amount = $("#amount").val();
        location.href = entry_url + "cart.php?item_id=" + item_id + "&amount=" . amount;
    });

    $('#address_search').click(function() {

        var zip = $('#zip').val();

        var entry_url = $('#entry_url').val();

        if (zip.match(/[0-9]{7}/) === null) {

            alert('正確な郵便番号を入力してください。');
            return false; // ページ遷移しない
        } else {
            $.ajax({
                type : "get",
                url : entry_url + "/postcode_search.php?zip=" + escape(zip),
            }).then(
                function(data){
                    if (data == 'no' || data == '') {
                        alert('該当する郵便番号がありません');
                    } else {
                        $('#address').val(data);
                    }
                },
                function(data) {
                    alert('読み込みに失敗しました。')
                },
            );
        }
    });
});