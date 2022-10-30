// import {Link} from "@inertiajs/inertia-react";
import route from 'ziggy-js'

const Home = (props) => {
    return (
        <div className='flex flex-col items-center justify-center min-h-screen min-w-screen'>
            <h1 className='text-white text-4xl font-thin'>Leystryku Support System</h1>
            {props.auth.user ? (
                // <div>
                //     <Link href={route('dashboard.index')}>
                //         <h2 className='text-white text-2xl font-ultrathin'>Goto Dashboard</h2>
                //     </Link>
                // </div>
                <div />
            ) : (
                <div>
                    <a href={route('login')}>
                        <h2 className='text-white text-2xl font-ultrathin'>Login</h2>
                    </a>
                </div>
            )}
        </div>
    )
}

export default Home;
