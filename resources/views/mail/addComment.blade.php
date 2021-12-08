
<style>
	.main {
		width: 100%;
		
	}
	.header {
		height: 40px;
		text-align: center;
		font-size: 14px;
		padding: auto;
		background-color:#17a2b8;
		display: table;
		width: 100%;
	}
	.header-title{
		display: table-cell;
		vertical-align: middle;
		margin:auto;
		color: #fff;
		font-weight: bold;
	}
	.footer {
		height: 30px;
		text-align: center;
		font-size: 12px;
		padding: auto;
		background-color:#17a2b8;
		display: table;
		width: 100%;
	}
	.footer-text {
		display: table-cell;
		vertical-align: middle;
		color: #fff;
		font-weight: bold;
	}
	.btn-div {
		display: table;
		text-align: center;
		margin: 30px auto;
	}
	a.button {
		display: table-cell;
		vertical-align: middle;
		height: 20px;
		font-weight: 700;
		color: white;
		text-decoration: none;
		padding: .8em 1em calc(.8em + 3px);
		border-radius: 3px;
		background: rgb(241,68,100);
		box-shadow: 0 -3px rgb(53,167,110) inset;
		transition: 0.2s;
		padding: 10px;
	} 
	a.button:hover { background: rgb(53, 167, 110); }
	
	
</style>
<div class="main">
	<div class="header"><div class="header-title">Мой сервис</div> </div>
	<div class="body">
		<p>Пользователь <a target="_blank" href="http://master702.ru/#/Users/{{$comment_author_id}}">{{ $comment_author }}</a> добавил комментарий под постом: <br />
			<i>{{$text}}</i>
		</p>
		<div class="btn-div">
			<a class="button"  href="http://master702.ru/#/Uslugi/{{$link_id}}">Посмотреть</a>
		</div>

	</div>
		<div class="footer">
		<div class="footer-text"><?php  $current_year = date ( 'Y' );?>
			<span>© master702.ru &nbsp; 2015 - <?php echo $current_year; ?></span> </div>
	</div>
</div>

