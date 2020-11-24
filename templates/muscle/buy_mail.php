<?php

echo '※このメールはシステムからの自動返信です。' . PHP_EOL;
echo $name . '様' . PHP_EOL . PHP_EOL;

echo 'MUSCLE STOREでのご購入ありがとうございます。' . PHP_EOL . PHP_EOL;

echo '以下の内容でご注文を受け付けいたしました。' . PHP_EOL;
echo '商品到着まで、今しばらくお待ちくださいませ。' . PHP_EOL . PHP_EOL;

echo '━━━━━━━━□■□　注文内容　□■□━━━━━━━━' . PHP_EOL;
echo 'お名前：' . $name . PHP_EOL;
echo 'E-Mail：' . $email . PHP_EOL . PHP_EOL;

echo '注文商品数：' . $sumNum . '点' . PHP_EOL;
echo '合計金額：' . number_format($sumPrice, 0, '.', ',') . '円' . PHP_EOL . PHP_EOL;

foreach ($dataArr as $key => $value) {
    echo '商品名：' . $value['item_name'] . PHP_EOL;
    echo '価格：' . number_format($value['price'], 0, '.', ',') . '円' . PHP_EOL;
    echo '数量：' . $value['num'] . '点' . PHP_EOL . PHP_EOL;
}
echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL . PHP_EOL;

echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL;
echo 'MUSCLE STORE' . PHP_EOL;
echo '担当：千葉 康平' . PHP_EOL;
echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL . PHP_EOL;