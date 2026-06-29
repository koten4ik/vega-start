
<form method="post" style="padding: 50px;">
    @csrf
    {{--отправитель емейл:<br>
    <input name="sender_mail" value="{{$sender_mail}}" autocomplete="off"><br><br>
    отправитель имя:<br>
    <input name="sender_name" value="{{$sender_name}}" autocomplete="off"><br><br>--}}
    шаблон:<br>
    <select name="template_id">
        @foreach(\Modules\ZSupport\App\Models\LetterTemplateModel::all() as $tmpl)
            <option value="{{$tmpl->name}}" @if(request()->template_id==$tmpl->name) selected="1" @endif>
                {{$tmpl->subject}}
            </option>
        @endforeach
    </select>
    <br><br>
    получатель емейл:<br>
    <input name="email" value="{{request()->email ?? ''}}" autocomplete="off"><br><br>
    {{--текст сообщения:<br>
    <textarea name="text" autocomplete="off">Тескст для тестового письма</textarea>
    <br><br>--}}
	<input type="hidden" name="submit" value="1">
    <button type="submit">Отправить</button>
    {{$rezult}}
</form>
