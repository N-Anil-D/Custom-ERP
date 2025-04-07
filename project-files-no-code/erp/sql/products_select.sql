SELECT 
users.id as user_id,
erp_warehouses.id as warehouses_id,
erp_warehouses.name as erp_warehouses_name,
erp_items_warehouses.item_id as erp_items_warehouses_item_id, 
erp_items_warehouses.amount as erp_items_warehouses_amount,
erp_items.id as erp_items_id, 
erp_items.code as erp_items_code,
erp_items.name as erp_items_name, 
erp_items.unit_id as erp_items_unit_id, 
erp_units.content as erp_units_content,
erp_items.type as erp_items_type, 
erp_items.content as erp_items_content

FROM 
rdglobal_portalapp.erp_warehouses as erp_warehouses left join 
rdglobal_portalapp.erp_items_warehouses as erp_items_warehouses on erp_items_warehouses.warehouse_id = erp_warehouses.id left join
rdglobal_portalapp.erp_items as erp_items on erp_items.id = erp_items_warehouses.item_id left join
rdglobal_portalapp.erp_users_warehouses as erp_users_warehouses on erp_users_warehouses.warehouse_id = erp_warehouses.id left join
rdglobal_portalapp.users as users on users.id = erp_users_warehouses.user_id inner join
rdglobal_portalapp.erp_units as erp_units on erp_units.id = erp_items.unit_id

where 
erp_warehouses.deleted_at is null and 
erp_items_warehouses.deleted_at is null and 
erp_items.deleted_at is null and
erp_users_warehouses.deleted_at is null and
erp_units.deleted_at is null and
erp_items.id is not null

order by erp_items.id