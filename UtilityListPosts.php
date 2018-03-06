<?php /* Template Name: Utility List Posts */
if(isset($_GET['type'])){
	$type=$_GET['type'];
	if(!Empty($type)){
		$Query=[
			'posts_per_page'=>-1,
			'orderby'=>'date',
			'order'=>'asc',
			'post_type'=>$type,
			'post_status'=>$_GET['status'],
		];
		$taxes=[
			'aaa'=>'aaas',
			//...
		];
		if(array_key_exists($type,$taxes)){
			$taxQuery=['field'=>'slug','taxonomy'=>$taxes[$type]];
			if(isset($_GET['terms'])){
				$terms=$_GET['terms'];
				if(!Empty($terms)){
					$taxQuery['terms']=explode(',',$terms);
					$Query['tax_query']=[$taxQuery];
				};
			};
		};
		unset($taxes,$taxQuery);
		$Query=new WP_Query($Query);//echo '<pre>',print_r($Query,true),'</pre>';
		if($Query->have_posts()){
			$totalPosts=0; 
			$campaigns=[];
			$founds=[];
			while($Query->have_posts()){
				$Query->the_post();
				$post=get_post();
				$postID=$post->ID;
				$item=[$postID,get_permalink($postID),get_the_title($postID)];
				$campaignId=get_post_meta($postID,'campaignId')[0];
				if(in_array($campaignId,$campaigns)){
					$founds[$campaignId][]=$item;
				}else{
					$campaigns[]=$campaignId;
					$founds[$campaignId]=[$item];
				};
				++$totalPosts;
			};
			unset($postID,$Query);
			wp_reset_postdata();
			$serviceName=[
				'666'=>'zzz',
			];
			ob_start();
			echo '<!DOCTYPE html><html><head><title>',$totalPosts,' found</title><base target="_blank"></head><body style="font:11px arial;"><h2>',$type,' <span style="color:#005598;">',$terms,'</span></h2><main style="padding: 0 0 0 1em;">';
			unset($totalPosts,$type,$terms);
			foreach($campaigns as $campaignId){
				$items=&$founds[$campaignId];
				echo '<h3 title="',$campaignId,'">',$serviceName[$campaignId],'<span style="font-weight: 500;">:&ensp;',count($items),'</span></h3><pre style="padding: 0 0 0 1em;">',PHP_EOL;
				foreach($items as &$item){
					echo '<a href="',$item[1],'">',$item[0],'<a> ',$item[2],PHP_EOL;
				};
				echo '</pre>',PHP_EOL;
				unset($items);
			};
			unset($founds,$campaignId,$serviceName);
			echo '</main></body></html>';
			ob_end_flush();
		};
	};
};
?>
