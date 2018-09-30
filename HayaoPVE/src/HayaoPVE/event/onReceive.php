<?php

namespace HayaoPVE\event;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\Task;
//use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\Zombie;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Enderman;
use pocketmine\entity\Villager;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Creeper;
use pocketmine\entity\Spider;
use pocketmine\entity\Witch;
use pocketmine\entity\IronGolem;
use pocketmine\entity\Blaze;
use pocketmine\entity\Slime;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\Horse;
use pocketmine\entity\Donkey;
use pocketmine\entity\Mule;
use pocketmine\entity\SkeletonHorse;
use pocketmine\entity\ZombieHorse;
use pocketmine\entity\Stray;
use pocketmine\entity\Husk;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\FallingSand;
use pocketmine\entity\Item as DroppedItem;
use pocketmine\entity\Skin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerTextPreSendEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerAnimationEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerUseFishingRodEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\TextContainer;
use pocketmine\event\Timings;
use pocketmine\event\TranslationContainer;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\AnvilInventory;
use pocketmine\inventory\BaseTransaction;
use pocketmine\inventory\BigShapedRecipe;
use pocketmine\inventory\BigShapelessRecipe;
use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\DropItemTransaction;
use pocketmine\inventory\EnchantInventory;
use pocketmine\inventory\FurnaceInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\enchantment\ProtectionEnchantment;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Armor;
use pocketmine\item\FoodSource;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\Durable;
use pocketmine\level\ChunkLoader;
use pocketmine\level\Explosion;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\WeakPosition;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\metadata\MetadataValue;
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
use pocketmine\network\Network;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddHangingEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemPacket;
use pocketmine\network\mcpe\protocol\AddPaintingPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\BlockPickRequestPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\CommandStepPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\CraftingEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\DropItemPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\HurtArmorPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryActionPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\MapInfoRequestPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\EntityFallPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\ReplaceItemInSlotPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\RiderJumpPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetCommandsEnabledPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\SetTitlePacket;
use pocketmine\network\mcpe\protocol\ShowCreditsPacket;
use pocketmine\network\mcpe\protocol\SpawnExperienceOrbPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\UnknownPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\SourceInterface;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\Plugin;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Sign;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\utils\Color;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use pocketmine\Player;
use pocketmine\Server;

use HayaoPVE\main;

class onReceive{
	function __construct(main $main){
		$this->main = $main;
	}

	public function onReceive($event){
		$pk = $event->getPacket();
		$p = $event->getPlayer();
		$name = $p->getName();
		if($pk instanceof InventoryTransactionPacket){
				$eid = $pk->trData->entityRuntimeId ?? null;
				if($eid === null){
					return false;
				}
				if(isset($this->main->entity[$eid])){
					$item = $p->getInventory()->getItemInHand();
					$damageTable = [
						Item::WOODEN_SWORD => 4,
						Item::GOLD_SWORD => 4,
						Item::STONE_SWORD => 5,
						Item::IRON_SWORD => 6,
						Item::DIAMOND_SWORD => 7,

						Item::WOODEN_AXE => 3,
						Item::GOLD_AXE => 3,
						Item::STONE_AXE => 3,
						Item::IRON_AXE => 5,
						Item::DIAMOND_AXE => 6,

						Item::WOODEN_PICKAXE => 2,
						Item::GOLD_PICKAXE => 2,
						Item::STONE_PICKAXE => 3,
						Item::IRON_PICKAXE => 4,
						Item::DIAMOND_PICKAXE => 5,

						Item::WOODEN_SHOVEL => 1,
						Item::GOLD_SHOVEL => 1,
						Item::STONE_SHOVEL => 2,
						Item::IRON_SHOVEL => 3,
						Item::DIAMOND_SHOVEL => 4,
					];
					$damage = $damageTable[$item->getId()] ?? 1;
					$damage = $this->main->atk[$name];
					if($this->main->entity[$eid]["damage"] == "true"){
						$this->main->mob_MobDamage->MobDamage($p, $eid, $damage, 1);
					}else{}
		}elseif($pk instanceof InteractPacket){
			$eid = $pk->target;
				if($eid === null){
					return false;
				}
				}elseif($eid === $this->main->k){
					$data = [
					    'type'    => 'form',
					    'title'   => '強化屋',
					    'content' => "何でもしていってくれ!!",
					    'buttons' => [
		 			    ['text' => "武器強化"],
		 			    ['text' => "武器進化"],
		 		     	['text' => "やめる"]
					    ]
				   ];
				   $this->main->createWindow($p, $data, 500);
				}elseif($eid === $this->main->q){
					$data = [
					    'type'    => 'form',
					    'title'   => 'クエスト',
					    'content' => "どのクエストを受注するんだい?",
					    'buttons' => [
		 			    ['text' => "GreenMonster討伐"],
		 		     	['text' => "やめる"]
					    ]
				   ];
				   $this->main->createWindow($p, $data, 9000);					
				}elseif($eid === $this->main->t){
					$data = [
						'type'    => 'form',
					    'title'   => 'ショップ',
					    'content' => "やぁ！！　今日は何買っていくんだい??",
					    'buttons' => [
					    	['text' => "All"],
					    	['text' => "剣士"],
					    	['text' => "ニコライト"],
					    	#['text' => "マジシャン"],
					    	#['text' => "アーチャー"]
					    	['text' => "オプションアイテム"],
		 		     		['text' => "やめる"]
					    ]
					];
					$this->main->createWindow($p, $data, 800);
				}elseif($eid === $this->main->b){
					$data = [
						'type'    => 'form',
					    'title'   => '売却屋',
					    'content' => "ワン!!ワンワンオ!!!!!",
					    'buttons' => [
		 			    ['text' => "売却する"],
		 			    ['text' => "売値を確認する"],
		 		     	['text' => "やめる"]
					    ]
					];
					$this->main->createWindow($p, $data, 600);
				}elseif($eid === $this->main->o){
					$data = [
						'type'    => 'form',
					    'title'   => '酒屋',
					    'content' => "ようこそ、ぜひ飲んでいってくださいね",
					    'buttons' => [
		 			    ['text' => "水耐性の酒 1000M"],
		 		     	['text' => "やめる"]
					    ]
					];
					$this->main->createWindow($p, $data, 700);
				}elseif($eid === $this->main->h){
					$data = [
						'type'    => 'form',
					    'title'   => '全てを知る者',
					    'content' => "...",
					    'buttons' => [
			 			    ['text' => "ルール & サービスについて"],
			 			    ['text' => "職業について"],
			 			    ['text' => "武器について"],
			 			    ['text' => "強化について"],
			 		     	['text' => "とじる"]
					    ]
					];
					$this->main->createWindow($p, $data, 400);
				}elseif($eid === $this->main->g){
					$day = date("l");
					if($day === "Monday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[月曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "月曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 900);
					}elseif($day === "Tuesday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[火曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "火曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 910);
					}elseif($day === "Wednesday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[水曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "水曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 920);
					}elseif($day === "Thursday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[木曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "木曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 930);
					}elseif($day === "Friday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[金曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "金曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 940);
					}elseif($day === "Saturday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[土曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "土曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 950);
					}elseif($day === "Sunday"){
						$data = [
							'type'    => 'form',
						    'title'   => 'ガチャ[日曜日]',
						    'content' => "ようこそ!!  いいアイテムが当たるといいね!!",
						    'buttons' => [
				 			    ['text' => "日曜日ガチャ"],
				 			    ['text' => "イベントガチャ"],
				 			    ['text' => "ノーマルガチャ"],
				 		     	['text' => "とじる"]
						    ]
						];
						$this->main->createWindow($p, $data, 960);
					}
				}
		}
		if($pk instanceof ModalFormResponsePacket) {
			$id = $pk->formId;
			$data = $pk->formData;
			$result = json_decode($data);
			if($data == "null\n"){
			}else{
				$this->main->window_rule->onRule($id, $data, $p);
				$this->main->window_weapon->onWeapon($id, $data, $p);
				$this->main->window_sell->onSell($id, $data, $result, $p);
				$this->main->window_shop->onShop($id, $data, $p);
				$this->main->window_sakaya->onSakaya($id, $data, $p);
				$this->main->window_option->onOption($id, $data, $p, $result);
				
				$this->main->gatya_main->onGatya_Main($id, $data, $p);
			}
		}
	}

	public function onEventGatya($id, $data, $p){
		$data = [
			'type'    => 'form',
			'title'   => 'イベントガチャ',
			'content' => "期間限定で行われているガチャです\n期間限定アイテム排出もあるぞ!!\nガチャには宝石玉が必要\n",
			'buttons' => [
				['text' => "イベントガチャ"],
				['text' => "やめる"]
			]
		];
		$this->main->createWindow($p, $data, 980);
	}

	public function onNormalGatya($id, $data, $p){
		$data = [
			'type'    => 'form',
			'title'   => 'ノーマルガチャ',
			'content' => "ノーマルガチャはお金で引けるガチャです!!\n出るアイテムのレア度は低い\n",
			'buttons' => [
				['text' => "ノーマルガチャ"],
				['text' => "やめる"]
			]
		];
		$this->main->createWindow($p, $data, 990);
	}
}