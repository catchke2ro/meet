<?php
/**
 * @var Post[] $posts
 */

use app\models\Post;

$this->title = 'Aktivitás';
?>

<div class="postsIndex">

	<?php foreach ($posts as $post) { ?>
		<?php $firstTagSlug = $post->getFirstTagSlug();?>
		<div class="postItem <?=$firstTagSlug ? "postType-$firstTagSlug" : "";?>">
			<div class="postInner">
				<?php if ($post->getImageUrl()) { ?>
					<div class="imageWrapper">
						<img src="<?=$post->getImageUrl();?>" alt="<?=$post->title;?>" />
					</div>
				<?php } ?>
				<div class="titleWrapper">
					<h2><?=$post->title;?></h2>
					<ul class="tags">
						<?php foreach ($post->tags ?: [] as $tag) { ?>
							<li>#<?=$tag;?></li>
						<?php } ?>
					</ul>
				</div>
				<?php if ($post->getImageUrl()) { ?>
					<div class="imageWrapperOpened">
						<img src="<?=$post->getImageUrl();?>" alt="<?=$post->title;?>" />
					</div>
				<?php } ?>
				<div class="intro"><?=$post->intro;?><p class="moreLink"><a href="javascript:void(0)">Bővebben >></a></p></div>
				<div class="text"><?=$post->text;?><p class="lessLink"><a href="javascript:void(0)"><< Mutass kevesebbet</a></p></div>
			</div>
		</div>
	<?php } ?>

</div>