<?php
session_start();
$user_id = $_SESSION["user_id"];

echo $_SESSION["user_name"] . "さんでログイン中<br>";
echo '<a href="./menu.php" class="btn btn-info">menu</a>';


if (empty($_SESSION)) {
    header("Location: ./login_error.php");
}

include(dirname(__FILE__) . '/header.php');




// データベースへの接続
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。";
} catch (PDOException $e) {
    echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
}




// Check if the date is already registered in the database
$query = "SELECT COUNT(*) FROM calendar_data WHERE user_id = :user_id AND date = :date";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

// 今日の日付を取得
$today = date('Y-m-d');

// カレンダーの表示月を取得
if (isset($_GET['date'])) {
    $currentDate = $_GET['date'];
} else {
    $currentDate = $today;
}

// 表示月の年と月を取得
$year = date('Y', strtotime($currentDate));
$month = date('m', strtotime($currentDate));
$monthName = date('F', strtotime($currentDate));

// 前月と次月のリンク先の日付を計算
$prevMonth = date('Y-m', strtotime($currentDate . ' -1 month'));
$nextMonth = date('Y-m', strtotime($currentDate . ' +1 month'));

// 表示月の最初の日と最後の日を取得
$firstDayOfMonth = date('Y-m-d', strtotime($year . '-' . $month . '-01'));
$lastDayOfMonth = date('Y-m-t', strtotime($year . '-' . $month . '-01'));

// 表示月の日数を計算
$daysInMonth = date('t', strtotime($currentDate));

// カレンダーを表示するHTMLを生成
$html = '<div class="container"><div id="calender_header" style="text-align: center;"><h1>カレンダー</h1>';
$html .= '<a href="?date=' . $prevMonth . '">前月</a> ';
$html .= '<a href="?date=' . $nextMonth . '">次月</a>';
$html .= '<br>';
$html .= '<h2>' . $year . '年 ' . $monthName . '</h2></div>';
$html .= '<table>';
$html .= '<tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>';

// 最初の週の空白セルを追加
$html .= '<tr>';
$firstDayOfWeek = date('w', strtotime($firstDayOfMonth));
$html .= str_repeat('<td></td>', $firstDayOfWeek);

// カレンダーの日付セルを追加
$currentDay = 1;
for ($i = $firstDayOfWeek; $i < 7; $i++) {
    $date = $year . '-' . $month . '-' . $currentDay;
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    $html .= '<td';

    // Add different CSS class based on registration status
    if ($count > 0) {
        $html .= ' class="registered-date" style="background-color: red;"';
    } else {
        $html .= ' class="unregistered-date"';
    }

    $html .= '><a href="#" onclick="showInputField(' . $year . ', ' . $month . ', ' . $currentDay . ')">' . $currentDay . '</a></td>';

    $currentDay++;
}
$html .= '</tr>';

// 2週目以降の日付セルを追加
while ($currentDay <= $daysInMonth) {
    $html .= '<tr>';
    for ($i = 0; $i < 7; $i++) {
        if ($currentDay > $daysInMonth) {
            $html .= '<td></td>';
        } else {
            $date = $year . '-' . $month . '-' . $currentDay;
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            $html .= '<td';

            // Add different CSS class based on registration status
            if ($count > 0) {
                $html .= ' style="background-color: red;"';
            } else {
                $html .= ' class="unregistered-date"';
            }

            $html .= '><a href="#" onclick="showInputField(' . $year . ', ' . $month . ', ' . $currentDay . ')">' . $currentDay . '</a></td>';
        }
        $currentDay++;
    }
    $html .= '</tr>';
}

$html .= '</table>';

echo $html;
?>
<div id="registerd_explain">
    <div id="registerd_color"></div>
    <p style="text-align: center;">...すでに登録されている日付</p>
</div>
<script>
    function showInputField(year, month, day) {
        let inputContainer = document.getElementById("input-container");

        // フォームが既に表示されている場合は閉じる
        if (inputContainer.querySelector("form")) {
            inputContainer.innerHTML = '';
            return;
        }

        inputContainer.innerHTML = '';

        let yearInput = document.createElement("input");
        yearInput.type = "hidden";
        yearInput.name = "year";
        yearInput.value = year;

        let monthInput = document.createElement("input");
        monthInput.type = "hidden";
        monthInput.name = "month";
        monthInput.value = month;

        let dayInput = document.createElement("input");
        dayInput.type = "hidden";
        dayInput.name = "day";
        dayInput.value = day;

        let conditionInput = document.createElement("input");
        conditionInput.type = "number";
        conditionInput.min = "1";
        conditionInput.max = "5";
        conditionInput.name = "condition";

        let input = document.createElement("input");
        input.type = "text";
        input.name = "text";

        let submit = document.createElement("input");
        submit.type = "submit";
        submit.value = "登録する";

        let form = document.createElement("form");
        form.id = "input-form";
        form.method = "POST";
        form.action = "calender_check.php";

        form.appendChild(yearInput);
        form.appendChild(monthInput);
        form.appendChild(dayInput);
        form.appendChild(conditionInput);
        form.appendChild(input);
        form.appendChild(submit);

        inputContainer.appendChild(form);
    }
</script>

<div id="input-container"></div>
<!-- <button onclick="submitForm()">フォームを送信する</button> -->


<!-- カレンダーで開始日と終了日を選択するフォーム -->
<h2 style="margin: 2em;">平均を計算する</h2>
<form method="POST" action="" id="average_calculate" style="margin: 2.0em">
    <label for="start_date">開始日:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>" required>

    <label for="end_date">終了日:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>" required>

    <input type="submit" value="範囲の平均値を計算する">
</form>

<?php


// 開始日と終了日の初期値を設定（今日の日付）
$startDate = date('Y-m-d');
$endDate = date('Y-m-d');

// 開始日と終了日が送信された場合は更新する
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
}

// 選択した範囲の平均値を取得するクエリを実行
$query = "SELECT AVG(condition_value) AS average_condition FROM calendar_data WHERE date BETWEEN :start_date AND :end_date";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
$stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$averageCondition = $row['average_condition'];

// 平均値を利用する（例: 表示する、計算に使用するなど）
echo "<p style='font-weight: bold;'>結果の平均値: → " . $averageCondition . "</p>";


?>
<br>

</div>
</body>

</html>