<?php

echo '※このメールはシステムからの自動返信です。' . PHP_EOL;
echo $name . '様' . PHP_EOL . PHP_EOL;

echo 'MUSCLE STOREへのお問い合わせありがとうございます。' . PHP_EOL . PHP_EOL;

echo '以下の内容でお問い合わせを受け付けいたしました。' . PHP_EOL;
echo '3営業日以内に、担当 千葉 よりご連絡いたしますので' . PHP_EOL;
echo '今しばらくお待ちくださいませ。' . PHP_EOL . PHP_EOL;

echo '━━━━━━□■□　お問い合わせ内容　□■□━━━━━━' . PHP_EOL;
echo 'お名前：' . $name . PHP_EOL;
echo 'E-Mail：' . $email . PHP_EOL . PHP_EOL;

echo 'お問い合わせ内容：' . $dataArr['content'] . PHP_EOL;
echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL . PHP_EOL;

echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL;
echo 'MUSCLE STORE' . PHP_EOL;
echo '担当：千葉 康平' . PHP_EOL;
echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━' . PHP_EOL . PHP_EOL;