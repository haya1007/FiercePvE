<?php

namespace HayaoPVE\window;

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

class option{
	public $main;

	public function __construct(main $main){
		$this->main = $main;
	}

	public function onOption($id, $data, $p, $result){
		$name = $p->getName();
		$status_data = $this->main->getStatus($name);
		if($id === 891){
			if($data == 0){
				#var_dump($status_data);
				$data = [
					'type'    => 'form',
					'title'   => '§a§lステータス確認',
					'content' => "§aSTR: ".$status_data["str"]."\n§bVIT: ".$status_data["vit"]."\n§3HEL: ".$status_data["hel"]."\n§dDEX: ".$status_data["dex"]."\n§cAGI: ".$status_data["agi"]."\n§6INT: ".$status_data["int"]."\n§eCRI: ".$status_data["cri"]."\n",
					'buttons' => [
				 	 	['text' => "やめる"]
					]
				];
				$this->main->createWindow($p, $data, 892);
			}elseif($data == 1){
				$data = [
					'type'    => 'form',
					'title'   => '§a§lステータス振り分け',
					'content' => "§aステータス振り分けを行います\n§aどのステータスに振り分けますか??\n",
					'buttons' => [
						['text' => "STR : 攻撃力"],
						['text' => "VIT : 防御力"],
						['text' => "HEL : 総合体力"],
						['text' => "INT : MP上昇"],
						['text' => "DEX : 命中率"],
						['text' => "AGI : 回避力"],
						['text' => "CRI : クリティカル率"],
				 	 	['text' => "やめる"]
					]
				];
				$this->main->createWindow($p, $data, 893);
			}
		}elseif($id === 893){
			if($data == 0){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§aSTR',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\n現在のSTR: ".$status_data["str"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 894);
			}elseif($data == 1){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§bVIT',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\n現在のvit: ".$status_data["vit"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 895);
			}elseif($data == 2){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§3HEL',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\n現在のhel: ".$status_data["hel"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 896);
			}elseif($data == 3){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§6INT',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\n現在のint: ".$status_data["int"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 897);
			}elseif($data == 4){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§dDEX',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\nこのステータスは50が最大です\n現在のdex: ".$status_data["dex"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 898);
			}elseif($data == 5){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§cAGI',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\nこのステータスは30が最大です\n現在のagi: ".$status_data["agi"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 899);
			}elseif($data == 6){
				$data = [
					'type'    => 'custom_form',
					'title'   => '§l§eCRI',
					'content' => [
						[
							"type" => "label",
							"text" => "振り分ける量を指定してください\nこのステータスは30が最大です\n現在のcri: ".$status_data["cri"]."\n"
						],
						[
							"type"        => "input",
							"text"        => "振り分ける数 (Max: ".$status_data["point"].")",
							"placeholder" => "例) 5",
							"default"     => ""
						]
					],
				];
				$this->main->createWindow($p, $data, 1800);
			}
		}elseif($id === 894){
			$str = $result[1];
			if(is_numeric($str)){
				if($str <= $status_data["point"]){
					if($str > 0){
						$this->main->setStatus($name, "str", $status_data["str"] + $str);
						$this->main->setStatus($name, "point", $status_data["point"] - $str);
						$this->main->config[$name]->save();
						$p->sendMessage("§aSTRを".$str."上昇させました");
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 895){
			$vit = $result[1];
			if(is_numeric($vit)){
				if($vit <= $status_data["point"]){
					if($vit > 0){
						$this->main->setStatus($name, "vit", $status_data["vit"] + $vit);
						$this->main->setStatus($name, "point", $status_data["point"] - $vit);
						$this->main->config[$name]->save();
						$p->sendMessage("§aVITを".$vit."上昇させました");
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 896){
			$hel = $result[1];
			if(is_numeric($hel)){
				if($hel <= $status_data["point"]){
					if($hel > 0){
						$this->main->setStatus($name, "hel", $status_data["hel"] + $hel);
						$this->main->setStatus($name, "point", $status_data["point"] - $hel);
						$this->main->config[$name]->save();
						$p->sendMessage("§aHELを".$hel."上昇させました");
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 897){
			$int = $result[1];
			if(is_numeric($int)){
				if($int <= $status_data["point"]){
					if($int > 0){
						$this->main->setStatus($name, "int", $status_data["int"] + $int);
						$this->main->setStatus($name, "point", $status_data["point"] - $int);
						$this->main->config[$name]->save();
						$p->sendMessage("§aINTを".$int."上昇させました");
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 898){
			$dex = $result[1];
			if(is_numeric($dex)){
				if($dex <= $status_data["point"]){
					if($dex > 0){
						if($status_data["dex"] + $dex >= 51){
							$p->sendMessage("§l§cDEXは50が最大です、ステ振りは行われませんでした");
						}else{
							$this->main->setStatus($name, "dex", $status_data["dex"] + $dex);
							$this->main->setStatus($name, "point", $status_data["point"] - $dex);
							$this->main->config[$name]->save();
							$p->sendMessage("§aDEXを".$dex."上昇させました");
						}
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 899){
			$agi = $result[1];
			if(is_numeric($agi)){
				if($agi <= $status_data["point"]){
					if($agi > 0){
						if($status_data["agi"] + $agi >= 31){
							$p->sendMessage("§l§cAGIは30が最大です、ステ振りは行われませんでした");
						}else{
							$this->main->setStatus($name, "agi", $status_data["agi"] + $agi);
							$this->main->setStatus($name, "point", $status_data["point"] - $agi);
							$this->main->config[$name]->save();
							$p->sendMessage("§aAGIを".$agi."上昇させました");
						}
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}elseif($id === 1800){
			$cri = $result[1];
			if(is_numeric($cri)){
				if($cri <= $status_data["point"]){
					if($cri > 0){
						if($status_data["cri"] + $cri >= 31){
							$p->sendMessage("§l§cCRIは30が最大です、ステ振りは行われませんでした");
						}else{
							$this->main->setStatus($name, "cri", $status_data["cri"] + $cri);
							$this->main->setStatus($name, "point", $status_data["point"] - $cri);
							$this->main->config[$name]->save();
							$p->sendMessage("§aCRIを".$cri."上昇させました");
						}
					}else{
						$p->sendMessage("§l§c0より大きい数字で入力してください");
					}
				}else{
					$p->sendMessage("§l§cステータスポイントを上回っています");
				}
			}else{
				$p->sendMessage("§l§c数字で入力してください");
			}
		}
	}
}