<?php

namespace HayaoPVE;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\NoDynamicFieldsTrait;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Color;

use HayaoPvE\main;

class PvEItem{
	private $main;

	public function _construct(main $main){
		$this->main = $main;
	}

	public function onEnable(){
		#$this->test = Item::get(1)->setCustomName("§atestBlock ATK: 182729383");
		#Item::addCreativeItem($this->test);
		$item1 = Item::get(209, 0, 1)->setCustomName('ｴﾝﾄﾞポータルになんか生えてる');
		Item::addCreativeItem($item1);
		$item2 = Item::get(90, 0, 1);
		Item::addCreativeItem($item2);
		$item3 = Item::get(247, 0, 1);
		Item::addCreativeItem($item3);
		$item4 = Item::get(246, 0, 1);
		Item::addCreativeItem($item4);
		$item5 = Item::get(247, 1, 1);
		Item::addCreativeItem($item5);
		$item6 = Item::get(247, 2, 1);
		Item::addCreativeItem($item6);
		$item7 = Item::get(444, 0, 1);
		Item::addCreativeItem($item7);
		$item8 = Item::get(124, 0, 1);
		Item::addCreativeItem($item8);
		$item9 = Item::get(122, 0, 1);
		Item::addCreativeItem($item9);
		$item10 = Item::get(120, 4, 1);
		Item::addCreativeItem($item10);
		$item11 = Item::get(119, 0, 1)->setCustomName('ｴﾝﾄﾞポータル');
		Item::addCreativeItem($item11);
		$item12 = Item::get(95, 0, 1)->setCustomName('透明ブロック');
		Item::addCreativeItem($item12);

		//---------------------剣士初期-----------------------------------------------------------------------------------------------
		$this->item1 = Item::get(267, 0, 1)->setCustomName("§l§c始まりの剣 [剣士]\n§7ATK: 12\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$this->item1->setNamedTagEntry(new StringTag("name", "§l§c始まりの剣"));
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));
		$this->item1->setNamedTagEntry(new IntTag("status", 12));
		$this->item1->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item1->setNamedTagEntry(new StringTag("job", "剣士"));				
		$this->item1->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item1->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item1);

		$this->item2 = Item::get(298, 0, 1)->setCustomName("§l§c始まりの頭装備 [All]\n§7DEF: 8\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(85, 0, 155);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item2->setNamedTagEntry(new IntTag("customColor", $colorcode));					
		$this->item2->setNamedTagEntry(new StringTag("name", "§l§c始まりの頭装備 [All]"));	
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));
		$this->item2->setNamedTagEntry(new IntTag("def", 8));
		$this->item2->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item2->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item2->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item2->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item2);

		$this->item3 = Item::get(299, 0, 1)->setCustomName("§l§c始まりの胸装備 [All]\n§7DEF: 12\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(85, 0, 155);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item3->setNamedTagEntry(new IntTag("customColor", $colorcode));		
		$this->item3->setNamedTagEntry(new StringTag("name", "§l§c始まりの胸装備 [All]"));		
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));			
		$this->item3->setNamedTagEntry(new IntTag("def", 12));
		$this->item3->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item3->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item3->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item3->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item3);

		$this->item4 = Item::get(300, 0, 1)->setCustomName("§l§c始まりの脚装備 [All]\n§7DEF: 10\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(85, 0, 155);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item4->setNamedTagEntry(new IntTag("customColor", $colorcode));
		$this->item4->setNamedTagEntry(new StringTag("name", "§l§c始まりの脚装備 [All]"));		
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));					
		$this->item4->setNamedTagEntry(new IntTag("def", 10));
		$this->item4->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item4->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item4->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item4->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item4);

		$this->item5 = Item::get(301, 0, 1)->setCustomName("§l§c始まりの足装備 [All]\n§7DEF: 8\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(85, 0, 155);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item5->setNamedTagEntry(new IntTag("customColor", $colorcode));	
		$this->item5->setNamedTagEntry(new StringTag("name", "§l§c始まりの足装備 [All]"));		
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));				
		$this->item5->setNamedTagEntry(new IntTag("def", 8));
		$this->item5->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item5->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item5->setNamedTagEntry(new StringTag("type", "無"));	
		$this->item5->setNamedTagEntry(new IntTag("rarity", 0));	
		Item::addCreativeItem($this->item5);





		//---------------------アコライト初期-----------------------------------------------------------------------------------------------
		$this->item6 = Item::get(267, 0, 1)->setCustomName("§l§c始まりの剣 [アコライト]\n§7ATK: 10\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$this->item6->setNamedTagEntry(new StringTag("name", "§l§c始まりの剣"));
		$this->item6->setNamedTagEntry(new IntTag("kyouka", 0));
		$this->item6->setNamedTagEntry(new IntTag("status", 10));
		$this->item6->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item6->setNamedTagEntry(new StringTag("job", "アコライト"));				
		$this->item6->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item6->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item6);

		$this->item7 = Item::get(298, 0, 1)->setCustomName("§l§c始まりの頭装備 [All]\n§7DEF: 8\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(100, 215, 195);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item7->setNamedTagEntry(new IntTag("customColor", $colorcode));					
		$this->item7->setNamedTagEntry(new StringTag("name", "§l§c始まりの頭装備 [All]"));	
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));
		$this->item7->setNamedTagEntry(new IntTag("def", 8));
		$this->item7->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item7->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item7->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item7->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item7);

		$this->item8 = Item::get(299, 0, 1)->setCustomName("§l§c始まりの胸装備 [All]\n§7DEF: 12\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(100, 215, 195);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item8->setNamedTagEntry(new IntTag("customColor", $colorcode));		
		$this->item8->setNamedTagEntry(new StringTag("name", "§l§c始まりの胸装備 [All]"));		
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));			
		$this->item8->setNamedTagEntry(new IntTag("def", 12));
		$this->item8->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item8->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item8->setNamedTagEntry(new StringTag("type", "無"));	
		$this->item8->setNamedTagEntry(new IntTag("rarity", 0));	
		Item::addCreativeItem($this->item8);

		$this->item9 = Item::get(300, 0, 1)->setCustomName("§l§c始まりの脚装備 [All]\n§7DEF: 10\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(100, 215, 195);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item9->setNamedTagEntry(new IntTag("customColor", $colorcode));
		$this->item9->setNamedTagEntry(new StringTag("name", "§l§c始まりの脚装備 [All]"));	
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));						
		$this->item9->setNamedTagEntry(new IntTag("def", 10));
		$this->item9->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item9->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item9->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item9->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item9);

		$this->item10 = Item::get(301, 0, 1)->setCustomName("§l§c始まりの足装備 [All]\n§7DEF: 8\n§5無属性 強化回数: 0\n§6レア度§r: §7☆☆☆☆☆");
		$color = new Color(100, 215, 195);
		$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
		$this->item10->setNamedTagEntry(new IntTag("customColor", $colorcode));	
		$this->item10->setNamedTagEntry(new StringTag("name", "§l§c始まりの足装備 [All]"));	
		$this->item1->setNamedTagEntry(new IntTag("kyouka", 0));					
		$this->item10->setNamedTagEntry(new IntTag("def", 8));
		$this->item10->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item10->setNamedTagEntry(new StringTag("job", "All"));				
		$this->item10->setNamedTagEntry(new StringTag("type", "無"));		
		$this->item10->setNamedTagEntry(new IntTag("rarity", 0));
		Item::addCreativeItem($this->item10);

		$this->item11 = Item::get(301, 0, 1)->setCustomName("§l§aヒールクリスタル [アコライト]\n§7D回復量: 2ハート\n§5消費MP: 15 回復範囲: 4ブロック\n§6レア度§r: §e☆§7☆☆☆☆");
		$this->item11->setNamedTagEntry(new StringTag("name", "§l§aヒールクリスタル [アコライト]"));						
		$this->item11->setNamedTagEntry(new IntTag("sell", 1000));
		$this->item11->setNamedTagEntry(new StringTag("job", "アコライト"));
		$this->item11->setNamedTagEntry(new IntTag("rarity", 1));				
		Item::addCreativeItem($this->item11);


		#$item->setNamedTagEntry(new IntTag("mp", $data["mp"]));	





		//---------------------ショップ-----------------------------------------------------------------------------------------------
		$this->sitem2 = Item::get(341, 0, 1)->setCustomName("§l§aオプション\n§6レア度§r: §e☆☆☆☆☆");
		$this->sitem2->setNamedTagEntry(new StringTag("name", "オプション"));
		$this->sitem2->setNamedTagEntry(new IntTag("sell", 1));	
		$this->sitem2->setNamedTagEntry(new IntTag("rarity", 5));
		Item::addCreativeItem($this->sitem2);

		$this->sitem = Item::get(280, 0, 1)->setCustomName("§l§eひのきの棒 [All]\n§7ATK: 16\n§5無属性 強化回数: 0\n§6レア度§r: §e☆§7☆☆☆☆");
		$this->sitem->setNamedTagEntry(new StringTag("name", "§l§eひのきの棒"));
		$this->sitem->setNamedTagEntry(new IntTag("kyouka", 0));
		$this->sitem->setNamedTagEntry(new IntTag("status", 16));
		$this->sitem->setNamedTagEntry(new IntTag("sell", 500));
		$this->sitem->setNamedTagEntry(new StringTag("job", "All"));				
		$this->sitem->setNamedTagEntry(new StringTag("type", "無"));		
		$this->sitem->setNamedTagEntry(new IntTag("rarity", 1));
		Item::addCreativeItem($this->sitem);

	}
}