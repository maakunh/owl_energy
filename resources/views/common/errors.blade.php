<!-- resources/views/common/errors.blade.php -->
@if (count($errors) > 0) 
<!-- Form Error List -->
<div class="alert alert-danger">
     <div><strong> エラーメッセージ </strong></div>
     <div>
          <ul>
                 @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
                 @endforeach
                 <li>今日の結果を表示します。</li>
          </ul>
     </div>
</div>
@endif
