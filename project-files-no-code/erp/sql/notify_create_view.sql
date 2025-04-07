create view rdglobal_portalapp.notifications as 

SELECT 
users.id as user_id,
erp_approvals.id as erp_approvals_id,
erp_approvals.item_id as erp_approvals_item_id, 
erp_approvals.content as erp_approvals_content, 
erp_approvals.file as erp_approvals_file,
erp_approvals.type as erp_approvals_type,
erp_approvals.status as erp_approvals_status,
erp_approvals.notify as erp_approvals_notify,
erp_approvals.sender_user as erp_approvals_sender_user,
erp_approvals.dwindling_warehouse_id as erp_approvals_dwindling_warehouse_id,
erp_approvals.increased_warehouse_id as erp_approvals_increased_warehouse_id,
erp_approvals.amount as erp_approvals_amount,
erp_approvals.created_at as erp_approvals_created_at

FROM 
rdglobal_portalapp.users as users inner join 
rdglobal_portalapp.erp_users_warehouses as erp_users_warehouses on users.id = erp_users_warehouses.user_id inner join 
rdglobal_portalapp.erp_approvals as erp_approvals on erp_approvals.increased_warehouse_id = erp_users_warehouses.warehouse_id inner join
rdglobal_portalapp.erp_warehouses as erp_warehouses on erp_warehouses.id = erp_approvals.increased_warehouse_id 

where erp_users_warehouses.deleted_at is null and
erp_approvals.deleted_at is null and 
erp_warehouses.deleted_at is null


