 $query = Customer::find();
        $dateQuery = UserA410::find();
        
        /*****************************************************/           

        if(!empty($_SESSION['customer']['filter']['searchrow'])) 
        {
            $search = trim(strip_tags($_SESSION['customer']['filter']['searchrow']));
            
            $query->andWhere(['like', 'email', $search])
            ->orWhere(['like', 'mobile_phone', $search])
            ->orWhere(['like', 'org_name', $search])
            ->orWhere(['like', 'org_title', $search])
            ->orWhere(['like', 'org_inn', $search])
            ->orWhere(['like', 'contact_fio', $search]);

             if(!empty($_SESSION['customer']['filter']['dateFrom']) || !empty($_SESSION['customer']['filter']['dateTo']))
            {
                if(!empty($_SESSION['customer']['filter']['dateFrom']))
                    $dateQuery->andWhere(['>=', 'created_at', $_SESSION['customer']['filter']['dateFrom']]);

                if(!empty($_SESSION['customer']['filter']['dateTo']))
                    $dateQuery->andWhere(['>=', 'created_at', $_SESSION['customer']['filter']['dateTo']]);

                $dateQuery->orderBy('created_at');                   
            }

            if (!empty($_SESSION['customer']['filter']['dateFrom']) || !empty($_SESSION['customer']['filter']['dateTo'])) {
                // $dateQuery = UserA410::find()->select('id');
                $dateQuery->select('id');

                if (!empty($_SESSION['customer']['filter']['dateFrom']))
                    $dateQuery->andWhere(['>=', 'created_at', strtotime(Customer::dateFormat($_SESSION['customer']['filter']['dateFrom'], 'from')->model)]);

                if (!empty($_SESSION['customer']['filter']['dateTo']))
                    $dateQuery->andWhere(['<=', 'created_at', strtotime(Customer::dateFormat($_SESSION['customer']['filter']['dateTo'], 'to')->model)]);                     

                $sub = [];
                $sq = $dateQuery->all();
                foreach ($sq as $item)
                    $sub[] = $item->id;

                $query->andWhere(['in', 'user_id', $sub]);

            }

            if(!empty($_SESSION['customer']['filter']['cityId']))
                $query->andWhere(['city_id' => (int) $_SESSION['customer']['filter']['cityId']]);

            if(!empty($_SESSION['customer']['filter']['in_1c']) && $_SESSION['customer']['filter']['in_1c'] == 1)
                $query->andWhere(['!=', 'code_1c', 'null']);

            if(!empty($_SESSION['customer']['filter']['in_1c']) && $_SESSION['customer']['filter']['in_1c'] == 2)
                $query->andWhere(['code_1c' => null]);  

        } 
        else 
        {


            if(!empty($_SESSION['customer']['filter']['dateFrom']) || !empty($_SESSION['customer']['filter']['dateTo']))
            {
                if(!empty($_SESSION['customer']['filter']['dateFrom']))
                    $dateQuery->andWhere(['>=', 'created_at', $_SESSION['customer']['filter']['dateFrom']]);

                if(!empty($_SESSION['customer']['filter']['dateTo']))
                    $dateQuery->andWhere(['>=', 'created_at', $_SESSION['customer']['filter']['dateTo']]);

                $dateQuery->orderBy('created_at');
            }

            if (!empty($_SESSION['customer']['filter']['dateFrom']) || !empty($_SESSION['customer']['filter']['dateTo'])) {
                
                $dateQuery->select('id');

                if (!empty($_SESSION['customer']['filter']['dateFrom']))
                    $dateQuery->andWhere(['>=', 'created_at', strtotime(Customer::dateFormat($_SESSION['customer']['filter']['dateFrom'], 'from')->model)]);

                if (!empty($_SESSION['customer']['filter']['dateTo']))
                    $dateQuery->andWhere(['<=', 'created_at', strtotime(Customer::dateFormat($_SESSION['customer']['filter']['dateTo'], 'to')->model)]);

                $sub = [];
                $sq = $dateQuery->all();
                foreach ($sq as $item)
                    $sub[] = $item->id;

                $query->andWhere(['in', 'user_id', $sub]);

            }

            if(!empty($_SESSION['customer']['filter']['cityId']))
                $query->andWhere(['city_id' => (int) $_SESSION['customer']['filter']['cityId']]);

            if(!empty($_SESSION['customer']['filter']['in_1c']) && $_SESSION['customer']['filter']['in_1c'] == 1)
                $query->andWhere(['!=', 'code_1c', 'null']);

            if(!empty($_SESSION['customer']['filter']['in_1c']) && $_SESSION['customer']['filter']['in_1c'] == 2)
                $query->andWhere(['code_1c' => null]); 

        }

        /*****************************************************/

        $query->andwhere(['account_type' => Customer::ACCOUNT_TYPE_CLINIC]);
        $query->andWhere(['document_status' => Customer::DOCUMENT_STATUS_OK]);
        $query->andWhere(['!=', 'access_level', Customer::ACCESS_LEVEL_ZERO]); 
        $query->andWhere(['name_status' => null]);
              
        
        $query->orderBy(['org_description' => SORT_ASC]);  
        if(isset($_SESSION['customer']['sort']) && $_SESSION['customer']['sort'] == 'name_up')
            $query->orderBy(['org_description' => SORT_ASC]);   
       
        if(isset($_SESSION['customer']['sort']) && $_SESSION['customer']['sort'] == 'name_down')
            $query->orderBy(['org_description' => SORT_DESC]);

        if(isset($_SESSION['customer']['sort']) && $_SESSION['customer']['sort'] == 'date_up')
            $query->orderBy(['user_id' => SORT_DESC]);

        if(isset($_SESSION['customer']['sort']) && $_SESSION['customer']['sort'] == 'date_down')
            $query->orderBy(['user_id' => SORT_ASC]);
        
        // if(!isset($_SESSION['customer']['sort']))
        //         $query->orderBy('second_name');     

        $count = $query->count();
        $pages = new Pagination([
            'totalCount' => $count,           
            'pageSize' => 10,
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);

        $model = $query->offset($pages->offset)->limit($pages->limit)->all();