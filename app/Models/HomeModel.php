<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Controllers\Common;

class HomeModel extends Model
{
    var $result = null;
    var $limit = null;
    var $offset = null;

    public function getHome()
    {
        $query = "select
        *
    from
        (
        select
            'post' as type,
            p.timestamp,
            p.created_on,
            first_name,
            last_name,
            o.occupation_name,
            profile_pic,
            p.images,
            p.post as post,
            p.liked_by ,
            p.disliked_by ,
            p.is_business_post,
            p.business_id,
            b.outlet_name,
            '' as event,
            '' as description,
            '' as venue,
            '' as interested,
            '' as going,
            '' as start_date,
            '' as end_date,
            p.post_id,
            '' as event_id,
            p.created_by  as user_id
        from
            posts p
        left join users on
            p.created_by = users.id
        left join occupations o on
            users.occupation = o.occupation_id
        left join businesses b on
            p.business_id = b.business_id
        where
            p.is_visible = 1
            and b.is_visible = 1
            or p.is_business_post = 0
            or 1
    union all
        select
            'event' as type,
            e.timestamp,
            e.created_on,
            first_name,
            last_name,
            o.occupation_name,
            profile_pic,
            e.images,
            '' as post,
            '' as liked_by,
            '' as disliked_by,
            '' as is_business_post,
            '' as business_id,
            '' as outlet_name,
            e.event as event,
            e.description,
            e.venue,
            e.interested,
            e.going,
            e.start_date,
            e.end_date,
            '' as post_id,
            e.event_id,
            e.created_by  as user_id
        from
            events e
        left join users on
            e.created_by = users.id
        left join occupations o on
            users.occupation = o.occupation_id
        where
            e.is_visible = 1
    union all
        select
            'user' as type,
            timestamp,
            created_on,
            first_name,
            last_name,
            o.occupation_name,
            profile_pic,
            '' as images,
            '' as post,
            '' as liked_by,
            '' as disliked_by,
            '' as is_business_post,
            '' as business_id,
            '' as outlet_name,
            '' as event,
            '' as description,
            '' as venue,
            '' as interested,
            '' as going,
            '' as start_date,
            '' as end_date,
            '' as post_id,
            '' as event_id,
            u.id
        from
            users u
        left join occupations o on
            u.occupation = o.occupation_id
        where
            u.is_active = 1
            and u.is_verified = 1) as tmp
    order by
        timestamp desc";

        if ($this->limit != null && $this->offset != null) {
            $query = $query . " limit " . $this->limit . " offset " . $this->offset;
            //echo "offset limit is not empty";
        }
        $this->result = $this->db->query($query);
        $res = $this->result->getResult();
        if ($this->isResultEmpty())
            return Common::createResponse(STATUS_NO_DATA, "No Data");
        return Common::createResponse(STATUS_SUCCESS, "Home Fetched", $res);
    }

    private function isResultEmpty()
    {
        return $this->result->getNumRows() == 0;
    }
}
